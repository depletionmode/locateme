#-------------------------------------------------
#
# Project created by QtCreator 2010-07-28T19:33:27
#
#-------------------------------------------------

QT       += core gui network

TARGET = LocateMeClient
TEMPLATE = app


SOURCES += main.cpp\
        locatemeclient.cpp \
    positionthread.cpp

HEADERS  += locatemeclient.h \
    positionthread.h

CONFIG += mobility
MOBILITY = location

symbian {
    TARGET.UID3 = 0xe723d54f
    TARGET.CAPABILITY += Location
    TARGET.EPOCSTACKSIZE = 0x14000
    TARGET.EPOCHEAPSIZE = 0x020000 0x800000

    # Define rss file for autoboot
    autoStartBlock = \
    "SOURCEPATH      ." \
    "START RESOURCE 06000001.rss" \
    "END"

    MMP_RULES += autoStartBlock

    # Deploy rsc file to package.
    deployRscFile = "\"$${EPOCROOT}epoc32/data/06000001.rsc\" - \
    \"C:/private/101f875a/import/[06000001].rsc\""
    deployFiles.pkg_postrules += deployRscFile
    DEPLOYMENT += deployFiles
}

OTHER_FILES += \
    06000001.rss
