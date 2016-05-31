"""
Connect to professor's DBLP xml file, and grab all information of Thesis, Journal Articles, Conference and Workshop, and Books.

* this program is suitable for DBLP xml file with 'multiple papers'
* params: 1)'fileName': the output file name
          2)'url': professor's DBLP xml link 
* the col 'publtype' records whether the paper is informal publication.
* Special case: if <title> or <booktitle>contains extra tags, then this program cannot parse the contents of title or book title by itself. It will send the notification, and mark the content of title or booktitle as '????'.
 
"""

import urllib
import xml.etree.ElementTree as ET

import csv
import re
fileName = 'Stefan Schaal'
url = 'http://dblp.uni-trier.de/pers/xx/s/Schaal:Stefan.xml'

# Download the RSS feed and parse it
u = urllib.urlopen(url)
doc = ET.parse(u)

# Extract and output tags of interest
root = doc.getroot()
rs = root.findall('./r')

with open(fileName+'.csv', 'w') as outputCsvfile:
	
	fieldnames = ['category', 'publtype', 'author', 'editor', 'address', 'title', 'booktitle', 'pages', 'year', 'journal', 'volume', 'number', 'month', 'url', 'ee', 'cite', 'school', 'publisher', 'note', 'cdrom', 'crossref', 'isbn', 'chapter', 'series']

	writer = csv.DictWriter(outputCsvfile, fieldnames=fieldnames)
	writer.writeheader()

	for r in rs:
		category = ""
		publtype = ""
		author = ""
		editor = ""
		address = ""
		title = ""
		booktitle = ""
		pages = ""
		year = ""
		journal = "" 
		volume = ""
		number = ""
		month = ""
		url = ""
		ee = ""
		cite = ""
		school = ""
		publisher = ""
		note = ""
		cdrom = ""
		crossref = ""
		isbn = ""
		chapter = ""
		series = ""
			
		ignore = False
		#print "*******\n"+r[0].tag
		
		if r[0].tag == "article":
			category = "Journal Articles"
		elif r[0].tag == "inproceedings":
			category = "Conference and Workshop Papers"
		elif r[0].tag == "book":
			category = "Book"
		elif r[0].tag == "phdthesis" or r[0].tag == "mastersthesis":
			category = "Thesis"
		else:
			ignore = True
		
		if(not ignore):
			if "publtype" in r[0].attrib:
				publtype = r[0].get('publtype')
			
			for pubInfo in r[0]:
				
				if isinstance(pubInfo.text, str):
					pubInfo.text = unicode(pubInfo.text, "utf-8")
				
				#print pubInfo.tag
				#print pubInfo.text.encode("utf-8")
				
				if(pubInfo.tag == "author"):
					if(author != ""):
						author += "\n"
					author += pubInfo.text
					
				elif(pubInfo.tag == "editor"):
					if(editor != ""):
						editor += "\n"
					editor += pubInfo.text
					
				elif(pubInfo.tag == "address"):
					if(address != ""):
						address += "\n"
					address += pubInfo.text
					
				elif(pubInfo.tag == "title"):
					if len(pubInfo)>0:
						#<title> containts elements
						print "\nspecial title!!!!"
						print author
						title += "????"
					else:
						if(title != ""):
							title += "\n"
						title += pubInfo.text
					
				elif(pubInfo.tag == "booktitle"):
					if len(pubInfo)>0:
						#<booktitle> containts elements
						print "\nspecial booktitle!!!!"
						print author
						booktitle += "????"
					else:
						if(booktitle != ""):
							booktitle += "\n"
							booktitle += pubInfo.text
					
				elif(pubInfo.tag == "pages"):
					if(pages != ""):
						pages += "\n"
					pages += pubInfo.text
					
				elif(pubInfo.tag == "year"):
					if(year != ""):
						year += "\n"
					year += pubInfo.text
					
				elif(pubInfo.tag == "journal"):
					if(journal != ""):
						journal += "\n"
					journal += pubInfo.text
					
				elif(pubInfo.tag == "volume"):
					if(volume != ""):
						volume += "\n"
					volume += pubInfo.text
					
				elif(pubInfo.tag == "number"):
					if(number != ""):
						number += "\n"
					number += pubInfo.text
					
				elif(pubInfo.tag == "month"):
					if(month != ""):
						month += "\n"
					month += pubInfo.text
					
				elif(pubInfo.tag == "url"):
					if(url != ""):
						url += "\n"
					url += pubInfo.text
					
				elif(pubInfo.tag == "ee"):
					if(ee != ""):
						ee += "\n"
					ee += pubInfo.text
					
				elif(pubInfo.tag == "cite"):
					if(cite != ""):
						cite += "\n"
					cite += pubInfo.text
					
				elif(pubInfo.tag == "school"):
					if(school != ""):
						school += "\n"
					school += pubInfo.text
					
				elif(pubInfo.tag == "publisher"):
					if(publisher != ""):
						publisher += "\n"
					publisher += pubInfo.text
					
				elif(pubInfo.tag == "note"):
					if(note != ""):
						note += "\n"
					note += pubInfo.text
					
				elif(pubInfo.tag == "cdrom"):
					if(cdrom != ""):
						cdrom += "\n"
					cdrom += pubInfo.text
					
				elif(pubInfo.tag == "crossref"):
					if(crossref != ""):
						crossref != "\n"
					crossref += pubInfo.text
					
				elif(pubInfo.tag == "isbn"):
					if(isbn != ""):
						isbn += "\n"
					isbn += pubInfo.text
					
				elif(pubInfo.tag == "chapter"):
					if(chapter != ""):
						chapter += "\n"
					chapter += pubInfo.text
					
				elif(pubInfo.tag == "series"):
					if(series != ""):
						series += "\n"
					series += pubInfo.text
					
				#print pubInfo.tag
				#print pubInfo.text.encode("utf-8")
				
			writer.writerow({'category': category.encode("utf-8"), 'publtype': publtype.encode("utf-8"), 'author': author.encode("utf-8"), 'editor': editor.encode("utf-8"), 'address': address.encode("utf-8"), 'title': title.encode("utf-8"), 'booktitle': booktitle.encode("utf-8"), 'pages': pages.encode("utf-8"), 'year': year.encode("utf-8"), 'journal': journal.encode("utf-8"), 'volume': volume.encode("utf-8"), 'number': number.encode("utf-8"), 'month': month.encode("utf-8"), 'url': url.encode("utf-8"), 'ee': ee.encode("utf-8"), 'cite': cite.encode("utf-8"), 'school': school.encode("utf-8"), 'publisher': publisher.encode("utf-8"), 'note': note.encode("utf-8"), 'cdrom': cdrom.encode("utf-8"), 'crossref': crossref.encode("utf-8"), 'isbn': isbn.encode("utf-8"), 'chapter': chapter.encode("utf-8"), 'series': series.encode("utf-8")})
			
print "Done!!!"		
		