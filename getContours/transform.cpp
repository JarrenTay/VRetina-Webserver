#include "transform.h"
#include <cmath>

using namespace std;

Transform::Transform(int originalWidth, int originalHeight) {
    this->widthHalf = (double)originalWidth / 2;
    this->heightHalf = (double)originalHeight / 2;
}

SpherePoint Transform::toSpherical(int xCoordinate, int yCoordinate) {
    double xCo = (double)xCoordinate;
    double yCo = (double)yCoordinate;

    double rectX = (xCo - this->widthHalf) / this->widthHalf;
    double rectY = (yCo - this->heightHalf) / this->heightHalf;

    double sphereX = rectX * sqrt(1.0 - (pow(rectY, 2) / 2.0));
    double sphereY = rectY * sqrt(1.0 - (pow(rectX, 2) / 2.0));

    // The distance between the center of a unit sphere (0, 0, 0) and its surface is 1.
    // 3D distance formula: distance = sqrt(pow(x2 - x1, 2) + pow(y2 - y1, 2) + pow(z2 - z1, 2))
    // To calculate for Z, where distance = 1 and one coordinate is (0,0,0)
    // z = 
    double sphereZ = sqrt(1.0 - pow(sphereX, 2) - pow(sphereY, 2));
    return SpherePoint(sphereX, sphereY, sphereZ);
}
