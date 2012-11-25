from __future__ import division
import urllib
import urllib2
import cookielib
import re
import os
import MySQLdb
import xml.etree.ElementTree as etree

from config import db, api_path

def loadpage(url):
    url = "https://what.cd/" + url

    req = urllib2.Request(url)
    f = urllib2.urlopen(req)
    content = f.read()
    return content

def toBytes(value, type):
	if type == 'B':
		return float(value)
	if type == 'KB':
		return float(value) * 1024
	if type == 'MB':
		return float(value) * 1024 * 1024
	if type == 'GB':
		return float(value) * 1024 * 1024 * 1024
	if type == 'TB':
		return float(value) * 1024 * 1024 * 1024 * 1024

def pull_and_store(url, cursor):
	root = etree.fromstring(loadpage(url))
	children = root.getchildren()
	statlist = []
	for child in children:
		statary = []
		for stat in child.getchildren():
			if stat.text is None:
				break
			statary.append(stat.text)
		if len(statary) < 8:
			continue

		# fix for 0 B downloaded, for infinite ratio fuckers
		if float(statary[1]) == 0.0:
			statary[1] = 1
		
		statstuple = ( str(statary[2]), str(float(statary[0])/float(statary[1])), str(statary[1]), 'B',
					   str(statary[0]), 'B',  str(long(statary[0])-long(statary[1])), 'B',
					   str(statary[6]), str(statary[5]), str(statary[4]), str(statary[3]), str(statary[7]) )
		statlist.append(statstuple)

		# yes, I know this is a bad horrible thing to do
		sqlstr = 'INSERT INTO statistics (username, ratio, downloaded, downType, uploaded, upType, buffer, buffType, uploads, snatched, leeching, seeding, forumPosts) VALUES '

		for stat in statlist[0:len(statlist)-1]:
			sqlstr += str(stat) + ', '
		sqlstr += str(statlist[len(statlist)-1])

	cursor.execute(sqlstr)

# connect to MySQL db
conn = MySQLdb.connect(host = db['host'],
			user = db['user'],
			passwd = db['password'],
			db = db['db'])
cursor = conn.cursor()

# get user ids to lookup and build API url
cursor.execute("SELECT userid FROM usernames")
rows = cursor.fetchall()
ids = []
i = 0
while i < len(rows):
    idset = []
    for idx in range(i, i+64):
        try:
            idset.append(str(rows[idx][0]))
        except IndexError:
            pass
    ids.append(idset)
    i += 64

for idset in ids:
    idstr = '|'.join(idset)
    url = '%s?users=%s&stats=uploaded|downloaded|username|seeding|leeching|snatched|uploads|posts' % (api_path, idstr)
    pull_and_store(url, cursor)

cursor.close()
conn.close()
