#include <stdio.h>
#include <opencv2/opencv.hpp>
//#include <opencv2/imgproc.hpp>
//#include <opencv2/highgui.hpp>
#include <math.h>
#include <iostream>
#include <string>
#include <fstream>
#include "transform.h"

using namespace std;
using namespace cv;

const int THRESHOLD_VALUE = 254;
const int MAX_BINARY_VALUE = 255;
const int THRESHOLD_TYPE = 1;

int main(int argc, char** argv )
{
    if ( argc != 3 )
    {
        printf("usage: getContours <Image_Path> <label_name>\n");
        return -1;
    }
    Mat image;
    Mat grayImage;
    Mat convertedImage;
    image = imread( argv[1], 1 );
    string labelName = argv[2];
    if ( !image.data )
    {
        printf("No image data \n");
        return -1;
    }
    else
    {
        cvtColor(image, grayImage, CV_BGR2GRAY);
        threshold(grayImage, convertedImage, THRESHOLD_VALUE, MAX_BINARY_VALUE, THRESHOLD_TYPE);
        // cout << to_string(convertedImage.size().height) << " " << to_string(convertedImage.size().width) << endl;
    }

    vector<vector<Point> > outContours;
    findContours(convertedImage, outContours, CV_RETR_EXTERNAL, CV_CHAIN_APPROX_SIMPLE);

    string baseString = "";
    string transformString = "";

    int origHeight = convertedImage.size().height;
    int origWidth = convertedImage.size().width;

    double tempX = 0.0;
    double tempY = 0.0;
    double tempZ = 0.0;

    int currX = 0;
    int currY = 0;

    // Translate a rectangle into a sphere. http://mathproofs.blogspot.com/2005/07/mapping-square-to-circle.html
    // First, normalize to -1 to 1 for X and Y. Then use distance formula to calculate Z.
    Transform sphericalTransformation = Transform(origHeight, origWidth);
    SpherePoint sphericalPoint;

    for (int i = 0; i < outContours.size(); i++) {
        ofstream base(labelName + to_string(i) + ".node");
        ofstream transform(labelName + to_string(i) + ".transformed");

        baseString = to_string(outContours[i].size() / 10) + " 2 0 0\n";
        transformString = to_string(outContours[i].size() / 10) + " 3 0 0\n";

        int count = 0;
        for (int j = 0; j < outContours[i].size(); j = j + 10) {
            currX = outContours[i][j].x;
            currY = outContours[i][j].y;
            baseString = baseString + to_string(count) + " " + to_string(currX) + " " + to_string(currY) + "\n";
            sphericalPoint = sphericalTransformation.toSpherical(currX, currY);

            transformString = transformString + to_string(count) + " " + to_string(sphericalPoint.x) + " " + to_string(sphericalPoint.y) + " " + to_string(sphericalPoint.z) + "\n";
            count = count + 1;
        }
        base << baseString;
        transform << transformString;
        base.close();
        transform.close();
    }
    return 0;
}
