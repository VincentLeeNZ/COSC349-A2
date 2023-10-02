# COSC349-A2

[Admins](http://ec2-34-237-15-90.compute-1.amazonaws.com/) (Username and Password are both admin)  
[Customers](http://ec2-35-169-251-26.compute-1.amazonaws.com/)




This repository contains the source code required to create a mock online store in which admin users can insert/delete products and general users can view them. The store uses two ec2 intances which are connected to a single RDS service. The store also uses two S3 buckets to contain images that are used for each product. One bucket is used for storing the intitial image, another bucket is used for storing the resized image after the lambda function.




**THE EXAMPLE CODE IN THESE GUIDES ARE DIFFERENT TO THE CODE USED IN THE REPOSITORY. MAKE SURE TO USE THE CODE HERE. THIS INCLUDES THE SQL CODE TOO**

## Creating the two instances:  
Instructions for creating EC2 instances can be found here: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/get-set-up-for-amazon-ec2.html  

## Creating and Connecting to an RDS for MySQL intance:
Instructions can be found here: https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/CHAP_GettingStarted.CreatingConnecting.MySQL.html


## Installing a web server on your EC2 instance:
Instructions can be found here: https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/CHAP_Tutorials.WebServerDB.CreateWebServer.html

## Customising your websites:  
To add content to the Apache web server that connects to your DB instance:  
- While still connected to your EC2 instance, change the directory to /var/www/html  
- Place the php files from admin and users within the appropriate directories. Make sure to also include the inc folder and its contents.

## Creating S3 buckets:  
Complete step 1 from here: https://docs.aws.amazon.com/AmazonS3/latest/userguide/tutorial-s3-object-lambda-uppercase.html  
After you have created two buckets, edit each bucket policy to have something similar to this:

{
    "Version":"2012-10-17",  
    "Statement": [{  
        "Sid": "Allow Public Access to All Objects",  
        "Effect": "Allow",  
        "Principal": "*",  
        "Action": "s3:GetObject",  
        "Resource": "arn:aws:s3:::this-is-a-sample-s3/*"  
    }]   
}  
**You must change the Resource to match the name of your bucket!**  
Make sure you can upload images and see them on your browser if you click the link!!  

## Lambda Function  
**Set your run time to Python 3.9 or this will NOT work**  
A guide to seting up a lambda function can be found here: https://docs.aws.amazon.com/lambda/latest/dg/with-s3-tutorial.html. Skip to create the lambda function and follow the rest of the steps, make sure to use the zip file within this repository. 






### Sources:
https://docs.aws.amazon.com/lambda/latest/dg/with-s3-tutorial.html  
https://docs.aws.amazon.com/AmazonS3/latest/userguide/tutorial-s3-object-lambda-uppercase.html  
https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/get-set-up-for-amazon-ec2.html  
https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/CHAP_GettingStarted.CreatingConnecting.MySQL.html  
https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/CHAP_Tutorials.WebServerDB.CreateWebServer.html  



