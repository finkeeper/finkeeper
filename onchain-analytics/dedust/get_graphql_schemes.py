import requests


def fetch_schema():
    url = 'https://api.dedust.io/v3/graphql'

    query = """
    query IntrospectionQuery {
      __schema {
        types {
          name
          description
          fields {
            name
            description
            type {
              name
              kind
              ofType {
                name
                kind
              }
            }
            args {
              name
              description
              type {
                name
                kind
                ofType {
                  name
                  kind
                }
              }
            }
          }
        }
        queryType {
          name
        }
        mutationType {
          name
        }
        subscriptionType {
          name
        }
      }
    }
    """

    headers = {
        'accept': '*/*',
        'content-type': 'application/json',
        'origin': 'https://dedust.io',
        'referer': 'https://dedust.io/',
        'user-agent': 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Mobile Safari/537.36'
    }

    payload = {
        'query': query,
        'operationName': 'IntrospectionQuery'
    }

    try:
        response = requests.post(url, json=payload, headers=headers)
        response.raise_for_status() 
        schema_data = response.json()

        if 'data' in schema_data:
            types = schema_data['data']['__schema']['types']

            print("\n=== GraphQL Schema ===\n")

            for type_info in types:
                if not type_info['name'].startswith('__'):
                    print(f"\nType: {type_info['name']}")
                    if type_info['description']:
                        print(f"Description: {type_info['description']}")

                    if type_info.get('fields'):
                        print("Fields:")
                        for field in type_info['fields']:
                            field_type = field['type'].get('name') or field['type']['ofType']['name']
                            print(f"  - {field['name']}: {field_type}")
                            if field['args']:
                                print("    Arguments:")
                                for arg in field['args']:
                                    arg_type = arg['type'].get('name') or arg['type']['ofType']['name']
                                    print(f"      {arg['name']}: {arg_type}")

        return schema_data

    except requests.exceptions.RequestException as e:
        print(f"Error fetching schema: {e}")
        return None


if __name__ == "__main__":
    schema = fetch_schema()

    # Optionally save the raw schema to a file
    if schema:
        import json

        with open('schema.json', 'w') as f:
            json.dump(schema, f, indent=2)
        print("\nRaw schema has been saved to 'schema.json'")