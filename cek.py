import requests

url = "https://konektikacloud.biz.id/v1/chat/completions"

headers = {
    "Authorization": "Bearer knkt-H-ovNVt1CRaKxZ4bnIIOcGNE",
    "Content-Type": "application/json"
}

data = {
    "model": "konektika-thinking",
    "messages": [
        {
            "role": "user",
            "content": "Halo siapa kamu?"
        }
    ]
}

r = requests.post(url, headers=headers, json=data)

print(r.status_code)
print(r.text)