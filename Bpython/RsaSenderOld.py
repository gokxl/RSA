# -*- coding: utf-8 -*-
# function to encrypt data
#!C:\Users\Gokul\AppData\Local\Programs\Python\Python37-32\python.exe

import cgi
import os
#import mysql.connector
import PIL.Image as pimg

#function for generating RGB value from given int value

def getRGBfromI(RGBint):
    blue =  RGBint & 255
    green = (RGBint >> 8) & 255
    red =   (RGBint >> 16) & 255
    return red, green, blue

#function for generating int value from given RGB value

def getIfromRGB(rgb):
    red = rgb[0]
    green = rgb[1]
    blue = rgb[2]
    RGBint = (red<<16) + (green<<8) + blue
    return RGBint

def encryption(P,e,n):
    C = []
    new = []
    for i in P:
        new.append(ord(i))
    for i in new:
        C.append((int(int(i) ** e))%n)
    return C

def main():
    print('context-type:text/html\r\n\r\n')
    print('<body><center>')
    #print('<script>alert("Entering RsaSender.py")</script>')
    form=cgi.FieldStorage()
    mob=str(form.getvalue("user_mob_smpy"))
    print('<p> Mobile number %s </p>' %mob)
    recmob=str(form.getvalue("rec_mob_smpy"))
    mssg=str(form.getvalue("sender_msg"))
    #fle=form['filename']
    
    #fn=os.path.basename(fle.filename)
    #open("C:/xampp/htdocs/RSA/Bpython/images/"+fn,"wb").write(fle.file.read())
    
    #FileName = input("Enter File name of Publik key distribution ")
    FileName = 'Repository.txt'
    fd = open(FileName)
    n = int(fd.readline())
    e = int(fd.readline())
    fd.close()
  
    data1 = encryption(mssg,e,n)
    
    ImageToOpen = str(input("Enter Image to process : "))
    Image = pimg.open(ImageToOpen)
    ImageToProcess = Image.load()

    for j in range(16):
        #i in data1:
        r,g,b = getRGBfromI(data1[j])            # generate RGB value for each entry in cipher text
        #print(r,g,b)
        ImageToProcess[0,j] = (r,g,b)            # hiding data behind pixel (overriding values)
        #print(ImageToProcess[0,j])

    Image.save("s2.png")

    print('<html>')
    print('<h1>sender mobile number is %s</h1>'%mob)
    print('<h1>receiver mobile number is %s</h1>'%recmob)
    #print('<img src=Bpython/%s'%fn)
    print('</center></body></html>')

if __name__ == '__main__':
    main()