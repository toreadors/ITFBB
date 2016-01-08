ubgggggg#import mysql.connector
import datetime
import sys
import argparse

'''
Ablauf:
- Aufruf Programm mit Argumenten user, pass, thread-id, read, reply, post-new
- Auflistung der Optionen: list, read xyz, answer xyz

'''

# Parse all the arguments!
parser = parse.ArgumentParser(description='CLI for itfbb'

parser.add_argument('-h','--host', help='Hostname',required=True)
parser.add_argument('-u','--user', help='Username',required=True)
parser.add_argument('-p','--password', help='Password'required=True)
# parser.add_argument('-','--', help='')



# Verbinde zur DB
# cnx_db = mysql.connector.connect(user=$user, password=$pass, host='localhost', database='infosystem')

#print ("")



#cnx_db.close()


