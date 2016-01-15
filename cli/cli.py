import mysql.connector
import datetime
import sys
import argparse


# Parse all the arguments!
parser = argparse.ArgumentParser(description='CLI for itfbb')

# parser.add_argument('-','--', help='')
parser.add_argument('-u','--user', help='username', action='store', dest='username')
parser.add_argument('-p','--password', help='password', action='store', dest='password')

parser.add_argument('-l','--list', help='list threads', action='store', dest='ls_thread')
parser.add_argument('-r','--read', help='read thread, required ID', action='store', dest='thread')
parser.add_argument('-a','--answer', help='answer to thread, required ID', action='store', dest='')

parser.add_argument('-p','--post', help='post new thread', action='store', dest='')
parser.add_argument('-tt','--thread_titel', help='Thread Titel', action'store', dest='thread_titel')
parser.add_argument('-pti','--post_titel', help='Posting Titel', action'store', dest='post_titel')
parser.add_argument('-ptt','--post_text', help='Posting Text', action'store', dest='post_text')


args = parser.parse_args()

# Verbinde zur DB
connect_db = mysql.connector.connect(user='root', password='', host='localhost', database='infosystem')
except mysql.connector.Error as err:
  if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
    print("Something is wrong with your user name or password")
  elif err.errno == errorcode.ER_BAD_DB_ERROR:
    print("Database does not exist")
  else:
    print(err)
else:
# im "switch-case" dann die Querys absenden je nach gesetzten Argumenten

if(isset(args.username)):
	print "Bitte Benutzername angeben."
elif(isset(args.password)):
	print "Bitte Kennwort angeben."


if args.list:
	# liste threads auf
elif args.read:
	# lese thread
elif args.answer:
	# poste antwort in thread
elif args.post:
	# erstelle neuen thread
elif args.thread_titel:
	# Titel eines neuen Threads
elif args.post_titel:
	# Titel eines neuen Postings
elif args.post_text:
	# Textbody eines Threads/Postings







connect_db.close()


