#ifndef POSITIONTHREAD_H
#define POSITIONTHREAD_H

#include <QThread>
#include <QGeoPositionInfo>
#include <QGeoPositionInfoSource>

QTM_USE_NAMESPACE

class PositionThread : public QThread
{
    Q_OBJECT
public:
    explicit PositionThread(QObject *parent = 0); 

protected:
    void run();

private:
    QGeoPositionInfoSource *_source;

private slots:
    void positionUpdated(const QGeoPositionInfo &info);

};

#endif // POSITIONTHREAD_H
