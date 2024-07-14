"""
This is just a dummy server based on: https://realpython.com/python-http-server/

Garbage code but it does the thing so...

How to call the endpoint with CLI curl:

curl --location 'http://127.0.0.1:8201/' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'text=Kierowca kat. C+E - System 2na2'
"""

import http.server
import socketserver
from urllib.parse import urlparse
from urllib.parse import parse_qs
import json

from datetime import datetime

"https://github.com/MaartenGr/KeyBERT#usage"
from keybert import KeyBERT

"Added here because this downloads something when initialised"
kw_model = KeyBERT()

class MyHttpRequestHandler(http.server.SimpleHTTPRequestHandler):
    def do_GET(self):
        try:
            jsonOutput = json.dumps({
                "info": "GET IS NOT SUPPORTED!"
            })

            self.send_response(400)
            self.send_header("Content-Type", "application/json")
            self.end_headers()
            self.wfile.write(bytes(jsonOutput, "utf8"))

            return
        except Exception as exc:
            self.log(exc)


    def do_POST(self):
        try:
            length = int(self.headers.get('content-length'))
            field_data = self.rfile.read(length)
            postData = parse_qs(str(field_data,"UTF-8"))
            if postData.get('text') is None:
                self.send_response(400)
                self.send_header("Content-Type", "application/json")
                self.end_headers()
                jsonOutput = json.dumps({
                    "keywords": [],
                    "success": 0,
                    "code": 400,
                    "msg": "No key named `text` was delivered in request, this is required"
                })
                self.wfile.write(bytes(jsonOutput, "utf8"))

            keywords = kw_model.extract_keywords(postData.get('text'), keyphrase_ngram_range=(1, 1), stop_words=None)

            jsonOutput = json.dumps({
                "keywords": keywords,
                "success": 1,
                "code": 200,
                "msg": "OK"
            })

            self.send_response(200)
            self.send_header("Content-Type", "application/json")
            self.end_headers()
            self.wfile.write(bytes(jsonOutput, "utf8"))

            return
        except Exception as exc:
            self.log(exc)

    "Write exception to log file"
    def log(self, exc: Exception) -> None:
         now = datetime.now() # current date and time

         # creating/opening a file
         f = open("/var/www/html/log.log", "a")

         # writing in the file
         f.write("\n")
         f.write("[" + now.strftime("%m/%d/%Y, %H:%M:%S") + "] ")
         f.write(str(exc))

         # closing the file
         f.close()

# Create an object of the above class
handler_object = MyHttpRequestHandler

PORT = 8765
my_server = socketserver.TCPServer(("", PORT), handler_object)

# Star the server
my_server.serve_forever()