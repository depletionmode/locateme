#include "locatemeclient.h"

#include <QGeoCoordinate>
LocateMeClient::LocateMeClient(QWidget *parent)
    : QMainWindow(parent)
{
    _positionThread.start();
}

LocateMeClient::~LocateMeClient()
{
    _positionThread.exit();
}
