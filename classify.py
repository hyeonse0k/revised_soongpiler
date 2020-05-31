import os
import sys
import shutil
import numpy as np
import PIL.Image as pilimg

path = "C:\Bitnami\wampstack-7.3.18-0/apache2\htdocs\Controller\photos/"
class_list = os.listdir(path)
for CLASS in class_list:
    if CLASS == "class_label.txt":
        continue
    file_name = os.listdir(path + CLASS + "/")
    if not os.path.exists(path + CLASS + "/train"):
        os.mkdir(path + CLASS + "/" + "train")
    if not os.path.exists(path + CLASS + "/test"):
        os.mkdir(path + CLASS + "/test")
    cnt = 1
    for file in file_name:
        if os.path.isdir(path+CLASS+"/"+file):
            continue
        if cnt % 4 == 0:
            shutil.move(path + CLASS + "/" + file, path + CLASS + "/test/" + file)
        else:
            shutil.move(path + CLASS + "/" + file, path + CLASS + "/train/" + file)
        cnt += 1
