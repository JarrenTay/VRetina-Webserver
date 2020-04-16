#include "spherePoint.h"

using namespace std;

class Transform {
public:
    Transform(int originalWidth, int originalHeight);
    SpherePoint toSpherical(int xCoordinate, int yCoordinate);
private:
    double widthHalf;
    double heightHalf;
};
