# VRetina-Webserver

This Webserver is hosted on an Amazon EC2 Server hosting AMI Linux 2.

I followed this guide: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ec2-lamp-amazon-linux-2.html
  to set up and install apache, php, and mysql.
  
The webserver is currently hosted at http://ec2-13-58-160-235.us-east-2.compute.amazonaws.com/


To ssh into the webserver and for a list of passwords required, email jarrentayusa@gmail.com.


To request data via app or website, send a POST request to http://ec2-13-58-160-235.us-east-2.compute.amazonaws.com/request.php
  with request headers: {"id": id of retina_model, "clientType": "app" or "browser"}

For id of retina_model, see the table named "retina_models".

For clientType, "browser" will display the image and metadata in a browser friendly manner, whereas "app" will provide the image
  data and metadata in json format.


The database structure is currently:


Database Name: vretina


Table Name: retina_models

select * from retina_models;

| id (int11) | name (varchar100)                                                                  | x_size (int11) | y_size (int11) | filetype (tinyint4) | official (tinyint1) | uploaded (timestamp) |
|------------|------------------------------------------------------------------------------------|----------------|----------------|---------------------|---------------------|----------------------|
| 0          | California-Proliferative-Diabetic-RetinopathyDiabeticRetinopathy8.jpg              | 2800           | 1872           | 0                   | 1                   | 2020-03-24 18:40:45  |
| 1          | California-Retinal-Detachment-with-Horseshoe-TearRetinalHolesTearsDetachments1.jpg | 2808           | 1960           | 0                   | 1                   | 2020-03-24 18:41:20  |
| 2          | Color-APMPPE-OD-California.jpg                                                     | 3064           | 2600           | 0                   | 1                   | 2020-03-25 03:36:10  |
| 3          | Color-APMPPE-OS-California.jpg                                                     | 3072           | 2520           | 0                   | 1                   | 2020-03-25 03:36:50  |
| 4          | Color-Horseshoe-Tear-California_result.jpg                                         | 2936           | 1960           | 0                   | 1                   | 2020-03-25 03:37:29  |
| 5          | Color-PDR2-OD-California.jpg                                                       | 3096           | 2560           | 0                   | 1                   | 2020-03-25 03:38:04  |
| 6          | Color-Retinal-Detachment-California_result.jpg                                     | 2934           | 1792           | 0                   | 1                   | 2020-03-25 03:38:41  |
| 7          | Color-Severe-NPDR-California.jpg                                                   | 2856           | 2000           | 0                   | 1                   | 2020-03-25 03:39:16  |

Table Name: filetypes

select * from filetypes;


| id (tinyint4) | filetype (varchar10) |
|---------------|----------------------|
| 0             | jpg                  |


For any questions, please email jarrentayusa@gmail.com.
