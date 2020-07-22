import tensorflow as tf
import os
import sys
import shutil
import numpy as np
import PIL.Image as pilimg
import time

"""
while True:
    if not os.path.exists("C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller\\result"):
        print("Machine Learning dose not start yet!")
        time.sleep(7)
        continue
    if not os.path.exists("C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller\\result\snapshot.txt"):
        print("Auto Snapshot dose not start yet!")
        time.sleep(1)
        continue
    else:
        new_model = tf.keras.models.load_model("./Controller/result/my_model.h5")
        new_model.summary()
        path = "C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller/result/auto/"
        break
"""
new_model = tf.keras.models.load_model("./Controller/result/my_model.h5")
new_model.summary()
path = "C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller/result/auto/"

old_line_count,line_count = 0,0
while True:
    test_image = []
    test_label = []
    f = open("C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller\\result\snapshot.txt",'r')
    lines = f.readlines()
    old_line_count = line_count
    line_count = len(lines)
    if line_count < 1: #snapshot.txt에 아무런 png 파일이 입력되지 않은 경우 1초 대기후 다시 while문 반복
        print("snapshot.txt has nothing")
        f.close()
        time.sleep(1)
        continue
    if line_count == old_line_count: #snapshot.txt 파일에 변동이 없을 경우 1초 대기후 다시 while문 반복
        print("snapshot.txt dose not update")
        f.close()
        time.sleep(1)
        continue

    file = lines[-1]
    file = list(file)
    del file[-1] # \n 개행문자 제거
    file = "".join(file)
    im = pilimg.open(path + file)

    pix =  np.array(im)
    pix = pix.flatten()

    test_image.append(pix)
    test_image = np.array(test_image)

    test_image = test_image.reshape(test_image.shape[0],240,240,3)
    prediction = new_model.predict(test_image)
    if not os.path.exists("C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller\\result\\result.txt"):
        res_file = open("C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller\\result\\result.txt",'w')
    else:
        res_file = open("C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller\\result\\result.txt",'a')
    result = str(np.argmax(prediction[0]))
    result += "\n"
    res_file.write(result)
    res_file.close()
    f.close()
    print("result.txt is updated")
    time.sleep(0.3)
