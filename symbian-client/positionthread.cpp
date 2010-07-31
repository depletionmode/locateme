#include "positionthread.h"

#include <unistd.h>

#include <QTime>
#include <QNetworkRequest>
#include <QNetworkAccessManager>
#include <QDebug>

#define UPDATE_TIME 1  // minutes
#define URL "http://blog.2of1.org/"

PositionThread::PositionThread(QObject *parent) :
    QThread(parent)
{
    qDebug() << "Thread started";

    _source = QGeoPositionInfoSource::createDefaultSource(this);

    connect(_source,
            SIGNAL(positionUpdated(QGeoPositionInfo)),
            this,
            SLOT(positionUpdated(QGeoPositionInfo)));

    _source->setUpdateInterval(3000);
    _source->setPreferredPositioningMethods(QGeoPositionInfoSource::AllPositioningMethods);
    _source->startUpdates();
}

void PositionThread::run()
{
    QTime timer;
    timer.start();

    qDebug() << "Timer started";

    while(1) {
        ::usleep(3000000);
        //qDebug() << "Timer loop: " << timer.elapsed() << "ms";
        if (timer.elapsed() > UPDATE_TIME * 60 * 1000) {
            _source->startUpdates();
            timer.restart();
            qDebug() << "Timer restarted";
        }
    }
}

void PositionThread::positionUpdated(const QGeoPositionInfo &info)
{
    qDebug() << "Position updated";

    _source->stopUpdates();

    double lng, lat;
    qreal accuracy;

    if (info.coordinate().isValid()) {
       lng = info.coordinate().longitude();
       lat = info.coordinate().latitude();
       //accuracy = info.attribute(QGeoPositionInfo::HorizontalAccuracy);
       accuracy = 1;
    }

    QString url(URL);
    url.append("?lon=");
    url.append(QString::number(lng));
    url.append("&lat=");
    url.append(QString::number(lat));
    url.append("&accuracy=");
    url.append(QString::number((int)accuracy));
    qDebug() << url;

    QNetworkAccessManager *manager = new QNetworkAccessManager(this);
    manager->get(QNetworkRequest(QUrl(url)));
}
