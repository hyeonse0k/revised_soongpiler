import tensorflow as tf
import os
import sys
import shutil
import numpy as np
import PIL.Image as pilimg
import datetime

path = "C:/Bitnami/wampstack-7.3.18-0/apache2/htdocs/Controller/photos/"
class_list = os.listdir(path)
train_images = []
test_images = []
train_labels = []
test_labels = []

count = 0
for CLASS in class_list:
    if CLASS == "class_label.txt":
        continue
    file_name = os.listdir(path + CLASS + "/" + "train")
    for file in file_name:
        im = pilimg.open(path + CLASS + "/" + "train/" + file)
        pix =  np.array(im)
        pix = pix.flatten()
        train_images.append(pix)
        train_labels.append(count)
        data = (file,count)
    file_name = os.listdir(path + CLASS + "/" + "test")
    for file in file_name:
        im = pilimg.open(path + CLASS + "/" + "test/" + file)
        pix =  np.array(im)
        pix = pix.flatten()
        test_images.append(pix)
        test_labels.append(count)
    count += 1

test_images = np.array(test_images)
train_images = np.array(train_images)
test_labels = np.array(test_labels)
train_labels = np.array(train_labels)

train_images = train_images.reshape(train_images.shape[0],240,240,3)
test_images = test_images.reshape(test_images.shape[0],240,240,3)
#이미지 읽어와서 reshape 끝

# 픽셀 값을 0~1 사이로 정규화합니다.
train_images, test_images = train_images / 255.0, test_images / 255.0

model = tf.keras.models.Sequential()

#입력받은 txt 파일에서 값을 받아와 conv2d, maxpooling2d파일 실행하기
f = open("C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller/param.txt",'r')
lines = f.readlines()
for i in lines:
    i = i.split(" ")
    print(i)
    if i[0] == "Conv2D":
        model.add(tf.keras.layers.Conv2D(i[1], (int(i[2]), int(i[2])), activation='relu', input_shape=(240, 240, 3)))
    else:
        model.add(tf.keras.layers.MaxPooling2D((int(i[1]), int(i[1]))))

#model.add(tf.keras.layers.Conv2D(32, (3, 3), activation='relu', input_shape=(240, 240, 3)))
#model.add(tf.keras.layers.MaxPooling2D((2, 2)))
#model.add(tf.keras.layers.Conv2D(64, (3, 3), activation='relu'))
#model.add(tf.keras.layers.MaxPooling2D((2, 2)))
#model.add(tf.keras.layers.Conv2D(64, (3, 3), activation='relu'))

model.summary()

label_file = open("C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller/photos/class_label.txt",'r')
class_count = label_file.readlines()
class_count = len(class_count)
model.add(tf.keras.layers.Flatten())
model.add(tf.keras.layers.Dense(64, activation='relu'))
model.add(tf.keras.layers.Dense(class_count, activation='softmax'))

model.summary()

model.compile(optimizer='adam',
              loss='sparse_categorical_crossentropy',
              metrics=['accuracy'])

log_dir = "C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller\logs\\" + datetime.datetime.now().strftime("%Y%m%d-%H%M%S")
tensorboard_callback = tf.keras.callbacks.TensorBoard(log_dir=log_dir, histogram_freq=1)
model.fit(train_images, train_labels, epochs=5, callbacks=[tensorboard_callback])

test_loss, test_acc = model.evaluate(test_images,  test_labels, verbose=2)
if not os.path.exists("C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller\\result"):
    os.mkdir("C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller\\result")
model.save("C:\Bitnami\wampstack-7.3.18-0\\apache2\htdocs\Controller\\result\my_model.h5")
