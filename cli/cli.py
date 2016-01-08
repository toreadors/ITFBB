import mysql.connector
import datetime
import sys
import argparse

'''
Ablauf:
- Aufruf Programm mit Argumenten user, pass, thread-id, read, reply, post-new
- Auflistung der Optionen: list, read xyz, answer xyz

'''

# Parse all the arguments!
parser = argparse.ArgumentParser(description='CLI for itfbb'

parser.add_argument('-h','--host', help='hostname',required=True)
parser.add_argument('-u','--user', help='username')
parser.add_argument('-p','--password', help='password')

parser.add_argument('-l','--list', help='list threads')
parser.add_argument('-r','--read', help='read thread, required ID')
parser.add_argument('-a','--answer', help='answer to thread, required ID')
parser.add_argument('-p','--post', help='post new thread')


# parser.add_argument('-','--', help='')

User Variable in pyhton eingabe ist nur für das Frontend Forum
im mysql connect sind statische Entrys, oben als VAR festlegen

# Verbinde zur DB
connect_db = mysql.connector.connect(user='args.user', password='args.password', host='args.host', database='infosystem')



connect_db.close()


