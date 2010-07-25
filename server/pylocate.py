#!/usr/bin/env python3

import socket, threading, time
import binascii
import sqlite3
import socketserver

db_path = 'pylocate-db.sqlite3'

def db_connect():
    conn = sqlite3.connect(db_path)
    return conn, conn.cursor()

def db_create_table():
    conn, c = db_connect()
        #id          integer primary key asc,
    c.execute("""create table positions (
        lon         float,
        lat         float,
        accuracy    int,
        timestamp   timestamp)""")
    conn.commit()

def db_save_location(lon, lat, accuracy):
    conn, c = db_connect()
    c.execute("insert into positions values (%s, %s, %s, datetime('now'))" % (lon, lat, accuracy))
    conn.commit()

def pos_convert(d, m, s):
    mm = m + (s / 60)
    dd = mm / 60
    return d + dd

def recv(s, size):
    msg = bytearray()
    while size > 0:
        chunk = s.recv(size)
        msg.extend(chunk)
        size -= len(chunk)
    s.close()
    return msg

def process(msg):
    lon_d = int(binascii.hexlify(msg[:3]), 16)
    lon_m = int(binascii.hexlify(msg[4:7]), 16)
    lon_s = int(binascii.hexlify(msg[8:15]), 16)
    lat_d = int(binascii.hexlify(msg[16:19]), 16)
    lat_m = int(binascii.hexlify(msg[20:23]), 16)
    lat_s = int(binascii.hexlify(msg[24:27]), 16)
    accuracy = int(binascii.hexlify(msg[28:31]), 16)

    lon = pos_convert(lon_d, lon_m, lon_s)
    lat = pos_convert(lon_d, lat_m, lat_s)

    db_save_location(lon, lat, accuracy)


class Handler(socketserver.BaseRequestHandler):
    def handle(self):
        self.data = self.request.recv(32).strip()
        process(self.data)

#db_create_table()

server = socketserver.TCPServer(('',2323), Handler)
server.serve_forever()
