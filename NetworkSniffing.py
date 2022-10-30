import sys
import time
import warnings
# -*- coding: utf-8 -*-
import mysql.connector


import progressbar
import pyshark
from scapy.all import *
"""
 Ci-dessus les modules python utilisés dans notre programme 
"""



def capture_on_interface(interface,timeout=86400):
    """
    :param interface: Nom de l'interface réseau sur laquelle l'écoute sera effectuée. L'interface par défaut dans le programme est celle du réseau sans fil
    :param timeout: Durée  en secondes de l'écoute du réseau. Par défaut elle est de 86400 secondes , l'équivalent d'un jour
    """

    """Ceci est le programme principal du projet d'écoute d'un réseau local
    """

    if timeout < 15:
        logger.error("Le delai doit etre superieur ou egal a 15 secondes\n.")
        return
        #delai d'ecoute >= 15
    if not sys.warnoptions:
        warnings.simplefilter("ignore")
    start = time.time()
    widgets = [
        progressbar.Bar(marker=progressbar.RotatingMarker()),
        ' paquets',
        progressbar.FormatLabel('Packets Captured: %(value)d'),
        'paquets ',
        progressbar.Timer(),
    ]
    progress = progressbar.ProgressBar(widgets=widgets)
    capture = pyshark.LiveCapture(interface=interface)
    #capture.set_debug()
    progress.start()
    progress.maxval = 100000000000

    """Ici commence l'analyse de chque paquet capturé pour récuperer les informations qui nous intéressent a savoir les adresses MAc et IP,les ports,
    les eventuelles url et les StreamIndex"""
    for i, paquet in enumerate(capture.sniff_continuously()):
        progress.update(i)



        if 'IP' in paquet:

            ipS = paquet.ip.src
            ipD = paquet.ip.dst

            if 'eth' in paquet:
                etherS = paquet.eth.src
                etherD = paquet.eth.dst
                print(etherS, "<<<--MAC-->>>", etherD)

            cu = None
            if 'udp' in paquet:
                cu = (paquet.udp.Stream)
                portS = paquet.udp.srcport
                portD = paquet.udp.dstport
                print(ipS, ":", portS, "<<<--IP-->>>", ipD, ":", portD)


            if 'tcp' in paquet:
                cu = (paquet.tcp.Stream)
                portS = paquet.tcp.srcport
                portD = paquet.tcp.dstport
                print(ipS, ":", portS, "<<<--IP-->>>", ipD, ":", portD)




            urls = None




            if 'http' in paquet:

                field_names = paquet.http._all_fields
                field_values = paquet.http._all_fields.values()
                #print(field_values)
                for field_name in field_names:
                    for field_value in field_values:
                        if field_name == 'http.request.full_uri' and field_value.startswith('http'):
                            urls = f'{field_value}'
                            print(urls)


            # connexion a la base de données
            cnx = mysql.connector.connect(
                host="localhost",
                user="barbe",
                password="1234expert",
                database='MesPaquets',
                auth_plugin='mysql_native_password'
            )
            # créer un curseur de base de données pour effectuer des opérations SQL
            cursor = cnx.cursor(buffered=True)

            """Insertion des données récupérées dans la base de données
            """

            query = ( "DELETE FROM paquets WHERE id NOT IN (SELECT * FROM ( SELECT MIN(id) FROM paquets   GROUP BY stream)  AS s_alias ); ")
            cursor.execute(query)


            query_string = ("INSERT INTO paquets (id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url,stream)"
                            "VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)")
            query_parameters = (None, str(ipS), str(ipD), str(etherS), str(etherD), str(portS), str(portD), str(urls), str(cu))

            string = ("INSERT INTO StreamIndex(str_id, stream)"
                           "VALUES (%s, %s)")
            parameters = (None, str(cu))
            cursor.execute(string, parameters)

            cursor.execute(query_string, query_parameters)

            #Pour s'assurer que les données ont bet et bien été enregistrées dans la table
            cnx.commit()
            cursor.close()


if __name__ == '__main__':
    """Appel de la fonction capture_on_interface"""

    capture_on_interface('wlo1',86400)


    """
     reference des sites : 
    
    1. Documentation pyshark_1 = https://thepacketgeek.com/pyshark/intro-to-pyshark/
    2. Documentation pyshark_2 = http://kiminewt.github.io/pyshark/
    3. Tuto youtube_1 = https://www.youtube.com/watch?v=1COC92XJluA
    4. Tuto youtube_2 = https://www.youtube.com/watch?v=gstHeldo61w
    5. stackoverflow = https://stackoverflow.com/questions/27025827/count-the-number-of-packets-with-pyshark
    """








