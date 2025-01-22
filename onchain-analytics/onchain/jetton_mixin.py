from typing import List, Dict, Union
import yaml
import os

class JettonReaderMixin:

    @staticmethod
    def parse_tokens_from_file(file_path: str) -> List[Dict[str, Union[dict, list]]]:
        tokens = []

        if not os.path.exists(file_path):
            raise FileNotFoundError(f"File '{file_path}' does not exist.")

        with open(file_path, 'r', encoding='utf-8') as file:
            try:
                token_data = yaml.safe_load(file)

                if isinstance(token_data, list):
                    for token in token_data:
                        symbol = token.get("symbol", None)
                        if symbol:
                            tokens.append({symbol: token})
                        else:
                            print(f"'symbol' not found in token: {token}")
                else:
                    print(f"Invalid data format in file: {token_data}")

            except yaml.YAMLError as e:
                print(f"Error parsing file {file_path}: {e}")

        return tokens
