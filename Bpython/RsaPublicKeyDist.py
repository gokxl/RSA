#!C:\Users\Gokul\AppData\Local\Programs\Python\Python37-32\python.exe

import math
import random as rm
import cgi
import mysql.connector


def isEven(num):
    if num & 1 == 0:
        return True
    return False

# function for finding out wheather number is prime or not

def isPrime(num):
    if isEven(num):
        return False
    for i in range(2 , int(math.sqrt(num))+1):
        if num % i == 0:
            return False
    else:
        return True

def GCD(small , big):
    for i in range(small , 0 , -1):
        if big % i == 0 and small % i == 0:
            return i
    else:
        return 1

def nmGenerator(p,q):
    return p*q , (p-1)*(q-1)

# function to generate PUBLIC KEY

def eGenerator(m):
    eArr = []
    for i in range(1,10):
        if GCD(i,m)==1:
            eArr.append(i)

    while True:
        e = rm.choice(eArr)
        if e > 1 and e < m:
            return e

# function to generate PRIVATE KEY

def dGenerator(e,m):
    ee = e % m
    for i in range(m):
        if (ee * i )% m== 1:
            return i

def main():
    
    print('context-type:text/html\r\n\r\n')
    form=cgi.FieldStorage()
    mob=str(form.getvalue("user_mob_rmpy"))
       
    PrimeList = [x for x in range(10,200) if isPrime(x)]
    
    while True:
        p = rm.choice(PrimeList)
        q = rm.choice(PrimeList)
        if p != q:
            break
            
    if isPrime(p) and isPrime(q):
        n,m = nmGenerator(p,q)

        e = eGenerator(m)
        fd = open("Repository.txt","w")
        fd.write(str(n)+"\n"+str(e))
        fd.close()
        d = dGenerator(e,m)
        
    mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="rsa"
    )

    mycursor = mydb.cursor()
    sql = "SELECT COUNT(*) FROM keyLog WHERE mobileNumber = %s"
    adr = (mob, )
    mycursor.execute(sql, adr)
    myresult = mycursor.fetchone()
       
    if myresult[0] == 0:
        sql = "INSERT INTO keyLog (mobileNumber, modulus, publicKey) VALUES (%s, %s, %s)"
        val = (mob, n, e)
        mycursor.execute(sql, val)
        mydb.commit() 
    else:
        sql = "UPDATE keyLog SET modulus = %s, publicKey = %s WHERE mobileNumber = %s"
        val = (n, e, mob)
        mycursor.execute(sql, val)
        mydb.commit()
        
    print('<html>')
    print('<body><center>')
    print('<h1>Please Remember Your Private Key is %s</h1>'%d)
    print('<h1>Public key is %s</h1>'%e)
    print('<h1>Modulus is %s</h1>'%n)
    print('</center></body></html>')
        
if __name__ == '__main__':
    main()

