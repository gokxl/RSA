#!C:\Users\Gokul\AppData\Local\Programs\Python\Python37-32\python.exe

import cgi,os
import mysql.connector
from PIL import Image 

#function for generating RGB value from given int value
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

def encryption(mesg,e,n):
    C = []
    new = []
    for i in mesg:
        new.append(ord(i))
    for i in new:
        C.append((int(int(i) ** e))%n)
    return C

def main():
    
    print('context-type:text/html\r\n\r\n')
    form=cgi.FieldStorage()
    mob=str(form.getvalue("user_mob_smpy"))
    recmob=str(form.getvalue("rec_mob_smpy"))
    mesg=str(form.getvalue("sender_msg"))
    fle=form['filename']
    fn=os.path.basename(fle.filename)
    open("C:/xampp/htdocs/RSA/Bpython/images/"+fn,"wb").write(fle.file.read())
    full_fn="C:/xampp/htdocs/RSA/Bpython/images/"+fn
   
    mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",    
    database="rsa"
    )

    mycursor = mydb.cursor()
    sql = "SELECT modulus,publicKey FROM keyLog WHERE mobileNumber = %s"
    adr = (recmob, )
    mycursor.execute(sql, adr)
    myresult = mycursor.fetchall()    
    
    print('<html>')
    print('<body><center>')
    #print('<h1>modulus is : %s</h1>'%n)
    #print("<h1>Total rows are: %s</h1>"%len(myresult))
    #print("Printing each row")
    for row in myresult:
       n=row[0]
       e=row[1]
    mycursor.close()
    
    print("<h1>message sent is: %s</h1>"%mesg)
    data1 = encryption(mesg,e,n)
    print("<h1>encrypted message is : %s </h1>"%data1)
    #print ('Passed input data1 is %s \n'%data1)
    
    img=Image.open(full_fn)
    px=img.load();
    
    #code to overide the pixel value with encrypted binary value 
    
    for j in range(len(mesg)):
        #i in data1:
        r,g,b = getRGBfromI(data1[j])   # generate RGB value for each entry in cipher text
        #print(r,g,b)
        px[0,j] = (r,g,b)            # hiding data behind pixel (overriding values)
        print(px[0,j])
    
    k=len(mesg)
    #insert into database the credentials
    
    insertMessage = mydb.cursor()
    sql_insert = "INSERT INTO messageLog (senderMob, receiverMob, msgLength) VALUES (%s, %s, %s)"
    insert_val = (mob, recmob, k)
    insertMessage.execute(sql_insert, insert_val)
    mydb.commit() 
    
    mesgID=mydb.cursor()
    sql_retrieve = "select max(msg_id) as mid from messageLog where senderMob = %s"
    adhere_mob = (mob, )
    mesgID.execute(sql_retrieve, adhere_mob)
    mymsg = mesgID.fetchone() 
    #print("the msg id is %s"%mymsg[0])
    temp_id = str(mymsg[0])
    #print("the msg id is %s"%temp_id)
     
    target_fn ="./enc_images/"+mob+"-"+temp_id+"-"+fn
    #print("\n image path is %s\n"%target_fn)
    img.save(target_fn)
    
    updateImage=mydb.cursor()
    sql_update = "UPDATE messageLog set stegImg = %s WHERE msg_id = %s"
    update_val = (target_fn, temp_id)
    updateImage.execute(sql_update, update_val)
    mydb.commit() 
    
    print('<table><tr><th>Sender Mobile</th><th>Receiver Mobile</th></tr>')
    print('<tr><td>%s</td>'%mob)
    print('<td>%s</td></tr></table><br><br>'%recmob)
    print('<table><tr><th>Source Image</th><th>Encrypted Image</th></tr>')
    print('<tr><td><img src=images/%s></td>'%fn)
    print('<td><img src=%s></td></tr></table>'%target_fn)
    print('</center></body></html>')

if __name__ == '__main__':  
    main()