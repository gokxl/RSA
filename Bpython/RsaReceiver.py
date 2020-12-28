#!C:\Users\Gokul\AppData\Local\Programs\Python\Python37-32\python.exe

import cgi
import mysql.connector
from PIL import Image 

# function to decrypt data

def getRGBfromI(RGBint):
    blue =  RGBint & 255
    green = (RGBint >> 8) & 255
    red =   (RGBint >> 16) & 255
    return red, green, blue

#function for generating int value from given RGB value

def getIfromRGB(rgb):
    red = rgb[0]
    #print('<h1>value of red is %s</h1>'%red)
    green = rgb[1]
    #print('<h1>value of greem is %s</h1>'%green)
    blue = rgb[2]
    #print('<h1>value of blue is %s</h1>'%blue)
    RGBint = (red<<16) + (green<<8) + blue
    #print('<h1>value of RGB is %s</h1>'%RGBint)
    return RGBint

def decryption(C,d,n):
    P = []
    new = []
    #print('<h1>Hi inside decrytpion</h1>')
    #print('<h1>Encrypted data value is %s</h1>'%C)
    #print('<h1>modulus values is %s</h1>'%n)
    #print('<h1>private key is %s</h1>'%d)
    for i in C:
        #print('<h1> encrypted value is %s </h1>'%i)
        new.append((int(int(i) ** int(d)))%n)
        
        
    for i in new:
        #print('<h1> decrypted value in new loop is %s</h1>'%i)
        P.append(chr(i))
    #print('<h1>message is %s</h1>'%P)
    return P

def main():
    
    print('context-type:text/html\r\n\r\n')
    form=cgi.FieldStorage()
    msg_id=str(form.getvalue("msglog_id"))
    priv_key=str(form.getvalue("PrivKey_rmbpy"))
    
    mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="rsa"
    )

    mycursor = mydb.cursor()
    sql = "SELECT stegImg,msgLength FROM messageLog WHERE msg_id = %s"
    adr = (msg_id, )
    mycursor.execute(sql, adr)
    myresult = mycursor.fetchall()
   
    for row in myresult:
       path_name=row[0]
       msg_len=row[1]   
    mycursor.close()
    
    print('<html>')
    print('<body><center>')
    #print('<h1>path name is %s</h1>'%path_name)
    print('<h1>msg length is %s</h1>'%msg_len) 
    
    mymodulus = mydb.cursor()
    sql_n = "SELECT modulus FROM keyLog WHERE mobileNumber in(Select receiverMob from messageLog where msg_id = %s)"
    adr_n = (msg_id, )
    mymodulus.execute(sql_n, adr_n)
    myresult1 = mymodulus.fetchone()
    
    n = myresult1[0]
    
    print('<h1>modulus values is %s</h1>'%n)
    
    img=Image.open(path_name)
    px=img.load();
    print('<h1>file open successful %s</h1>'%img)  
    #for i in range(30):
        #for j in range(30):        
            #print(" seatch rgb value of pixel %s "%str(px[i,j]))
            
    data1 = []
    for j in range(int(msg_len)):
        #print("rgb value of pixel %s "%str(px[0,j]))
        a = getIfromRGB(px[0,j])   # geting value of cipher text from image
        data1.append(a)
    print('<h1> RGBtoI value is %s </h1>'%data1)
     
    print('<img src=%s>'%path_name)
    
    C = data1
    d = priv_key    
    mesg = str(decryption(C,d,n))
    
    print('<h1>Message is %s</h1>'%mesg)
    print('</center></body></html>')

if __name__ == '__main__':
    main()