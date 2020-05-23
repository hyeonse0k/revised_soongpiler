#!C:\Users\gkrtj\AppData\Local\Programs\Python\Python37\python.exe
import os
import sys
import shutil
print("content-type:text/html; charset=UTF-8\n")
path = "C:/Bitnami/wampstack-7.3.17-0/apache2/htdocs/Controller/photos/"
class_list = os.listdir(path)

for CLASS in class_list:
    file_name = os.listdir(path + CLASS + "/")
    os.mkdir(path + CLASS + "/" + "train")
    os.mkdir(path + CLASS + "/" + "test")
    cnt = 1
    for file in file_name:
        if cnt % 4 == 0:
            shutil.move(path + CLASS + "/" + file, path + CLASS + "/test/" + file)
        else:
            shutil.move(path + CLASS + "/" + file, path + CLASS + "/train/" + file)
        cnt += 1
