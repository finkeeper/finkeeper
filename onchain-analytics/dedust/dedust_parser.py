import json
from datetime import datetime
from typing import NoReturn, List, Dict
from apscheduler.schedulers.blocking import BlockingScheduler
from apscheduler.events import EVENT_JOB_ERROR, EVENT_JOB_MISSED
import logging
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium import webdriver
from selenium.webdriver.chrome.service import Service as ChromeService
from webdriver_manager.chrome import ChromeDriverManager
from selenium.common.exceptions import TimeoutException

logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('pool_collector.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger('PoolCollector')


class Parser:
    def __init__(self) -> NoReturn:
        self.__op = webdriver.ChromeOptions()
        self.__op.add_argument('--no-sandbox')
        self.__op.add_argument('--disable-dev-shm-usage')
        self.__op.add_argument("--headless=new")
        self.driver = webdriver.Chrome(service=ChromeService(ChromeDriverManager().install()), options=self.__op)
        self.wait = WebDriverWait(self.driver, 20)
        self.BASE_URL = 'https://dedust.io/pools'

    def close_parser(self) -> NoReturn:
        try:
            self.driver.quit()
        except Exception as e:
            logger.error(f"Error closing driver: {e}")

    def get_pools_apr(self) -> List[Dict]:
        try:
            self.driver.get(url=self.BASE_URL)

            self.wait.until(
                EC.presence_of_element_located((By.CLASS_NAME, "app-earn__content-table-row"))
            )

            view_all_button = self.wait.until(
                EC.element_to_be_clickable(
                    (By.XPATH, "//div[contains(@class, 'app-earn__content-table-row--wide')]//span[text()='View all']")
                )
            )
            view_all_button.click()
            logger.info("click view all")
            pools_data = []
            pools = self.driver.find_elements(By.CLASS_NAME, "app-earn__content-table-row")

            for pool in pools:
                try:
                    pool_data = pool.find_elements(By.CLASS_NAME, "app-earn__content-table-cell")
                    if pool_data[0].text == 'Pair':
                        continue
                    pool_info = {
                        'name': pool_data[0].text.replace("\nSTABLE", ""),
                        'tvl': pool_data[1].text,
                        'volume': pool_data[2].text,
                        'fees': pool_data[3].text,
                        'apr': pool_data[4].text
                    }
                    pools_data.append(pool_info)
                except Exception as e:
                    logger.error(f"Error parsing pool: {e}")
                    continue

            return pools_data

        except TimeoutException:
            logger.error("Timeout waiting for elements to load")
            return []
        except Exception as e:
            logger.error(f"Error in get_pools_apr: {e}")
            return []


class PoolDataCollector:
    def __init__(self, output_file: str = 'pool_data.json'):
        self.output_file = output_file
        self.scheduler = BlockingScheduler()
        self.parser = None

    def save_data(self, data: List[Dict]) -> None:
        try:

            new_entry = {
                "timestamp": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
                "pools": data
            }

            data = list()
            data.append(new_entry)

            with open(self.output_file, 'w') as f:
                json.dump(data, f, indent=4, ensure_ascii=False)

            logger.info("Data successfully saved")
        except Exception as e:
            logger.error(f"Error saving data: {e}")

    def collect_data(self) -> None:
        try:
            if self.parser:
                self.parser.close_parser()

            self.parser = Parser()
            logger.info("Starting data collection")

            pools_data = self.parser.get_pools_apr()

            if pools_data:
                self.save_data(pools_data)
            else:
                logger.warning("No data collected")

        except Exception as e:
            logger.error(f"Error in collect_data: {e}")
        finally:
            if self.parser:
                self.parser.close_parser()

    def handle_job_error(self, event):
        logger.error(f"Job error occurred: {event.exception}")

    def handle_job_missed(self, event):
        logger.warning(f"Job missed: {event.job_id}")

    def run(self) -> None:
        try:
            self.scheduler.add_listener(self.handle_job_error, EVENT_JOB_ERROR)
            self.scheduler.add_listener(self.handle_job_missed, EVENT_JOB_MISSED)

            self.scheduler.add_job(
                self.collect_data,
                'interval',
                minutes=5,
                id='collect_pool_data',
                max_instances=1,
                next_run_time=datetime.now()
            )

            logger.info("Scheduler started")
            self.scheduler.start()

        except (KeyboardInterrupt, SystemExit):
            logger.info("Stopping scheduler...")
            self.scheduler.shutdown()
            if self.parser:
                self.parser.close_parser()


if __name__ == "__main__":
    collector = PoolDataCollector()
    collector.run()