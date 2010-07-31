#ifndef LOCATEMECLIENT_H
#define LOCATEMECLIENT_H

#include <QtGui/QMainWindow>

#include "positionthread.h"

class LocateMeClient : public QMainWindow
{
    Q_OBJECT

public:
    LocateMeClient(QWidget *parent = 0);
    ~LocateMeClient();

private:
    PositionThread _positionThread;
};

#endif // LOCATEMECLIENT_H
