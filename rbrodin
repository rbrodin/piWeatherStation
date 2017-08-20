# Programmed by Robert/TastyBrownies.
# Feel free to use it to your advantage. I programmed this code to work with any SSH tunnel and SQL database.
# Enjoy! I had a lot of fun working on this project and it is my pleasure. You can find the end result at: http://robdev.tech/weather

import MySQLdb
import datetime
import time
from random import randint

# Connects to mySQL database. I could've added arguments such as port, user, etc.
def connectToSQL():
    db = MySQLdb.connect(host="localhost",    # Hostname, which is usually local host.
                 user="USERNAME",         # The username in which you use to log into your database.
                 passwd="****",  # The password.
                 db="DATABASENAME", # The name of the database you are reading/writing to.
                 port=3306)        # The port you are connecting to. In my case, this is port 3309. 3306 is default, however Namecheap forced me to use an SSH tunnel with port 3309.

    return db

# Used to get the difference in temperature between the the current reading and the past reading.
def calculateDifference(numberOne, numberTwo):
    if int(numberOne) < int(numberTwo):
        return ("+" + str(numberTwo - numberOne))
    elif int(numberOne) > int(numberTwo):
        return ("-" + str(numberOne - numberTwo))
    else:
        return str(0)

'''
This function is used to update the table. There are two seperate tables, one for PHP to read the current data, and the other
is a log of all of the weather data for that specific day. The current_Weather table will only store one value. As you do not want php to have
to read a million values as it loops through. The second table is a complete log of all of the weather data from that day. This
data, at least in my case, will be used to plot graphs.

Function updateTable takes arguments tableList, which is all of the tables to be updated to, and c, an annoying variable I had to 
add because of local and global variable issues.

The function will write data to two tables.
'''
def updateTable(tableList, lT, cc):
    
    database = connectToSQL()
    cur = database.cursor()

    sense = SenseHat()
    
    currentTemperature = (sense.get_temperature() * 9/5) + 32
    currentHumidity = sense.get_humidity()
    currentPressure = sense.get_pressure() # * 0.014 - If you would like it in PSI, not Millibars. 
    difference_from_last = calculateDifference(lT[0], currentTemperature)

    deleteFromCurrent = "DELETE FROM current_Weather LIMIT 1"
    cur.execute(deleteFromCurrent)

    valuesToInsertCurrent = "insert into %s VALUES(%d, NOW(), %d, %d, %d, %s)" % (tableList[0], cc, currentTemperature, currentHumidity, currentPressure, difference_from_last)
    valuesToInsertLog = "insert into %s VALUES(%d, NOW(), %d, %d, %d, %s)" % (tableList[1], cc, currentTemperature, currentHumidity, currentPressure, difference_from_last)
    lT.remove(lT[0])
    lT.append(currentTemperature)
    cur.execute(valuesToInsertCurrent)
    cur.execute(valuesToInsertLog)

    database.close()

# Function will create a new table. Is used to create a new table at the start of every day. The format is: 'MONTHDAY,YEAR', or 'May12,2017'
def createTable():

    database = connectToSQL()
    cur = database.cursor()

    # Updates using the Datetime function.    
    date = str(now.strftime("%B%d%Y"))
    dayCheck = date

    cur.execute('''CREATE TABLE %s
                 (loggedTemperature int, loggedTime int, loggedDifference text)''' % (date))

    database.close()

# Function will run the script every minute to read and write new temperatures. Checks if there is a new day or not. If so, a new table is created.
def runScript():
    currentDate = datetime.datetime.now()
    counter = 0     
    last_temperature = [0]
    while True:
        if currentDate().date() != datetime.datetime.now().date():
            counter = 0
            currentDate = datetime.datetime.now()            
            createTable()
            updateTable(["current_Weather", str(currentDate.strftime("%B%d%Y"))], last_temperature, counter)
            counter = counter + 1
            print "Added - Date is different! ID: " + str(counter) 
            time.sleep(60)        
        else:       
            updateTable(["current_Weather", str(currentDate.strftime("%B%d%Y"))], last_temperature, counter)
            print "Added - Date is the same! ID: " + str(counter) 
            counter = counter + 1            
            time.sleep(60)

#runScript()       
createTable()
