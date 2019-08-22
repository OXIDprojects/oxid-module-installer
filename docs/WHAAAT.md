# Oxid Installer

## Für den Endanwender

Der Installer ist eine grafische Benutzeroberfläche (GUI oder auch GOI - Grafical Oxid Installer), welche mit Composer Pakete/Software und sogar Oxid selbst installieren, aktualisieren und löschen kann.

Pakete sind entweder Module welche für Oxid entwickelt wurden, oder Software die von Modulen gebraucht wird, damit das Rad nicht neu erfunden werden muss.

Geplant ist, den Installer als einzelne Datei auszuliefern. Diese Datei kann dann über den Browser aufgerufen und verwendet werden.

Zusätzlich werden im Installer selbst beliebte bzw. oft installierte Pakete und später auch kommerzielle Pakete vorgeschlagen.

### Was bringt das?

Anwender, Agenturen, Entwickler, Designer, etc. können nun einfach Module installieren. Kentnisse über Composer oder SSH werden nicht benötigt. Über das Programm könnten folgende Szenarien realisiert werden:

- Oxid über eine Datei auf dem Server installieren (Datei aufrufen und starten)
- Oxid mit Cronjobs aktuell halten
- Backup-Historie der Composer.json, damit im Notfall eine Version hergestellt werden kann
- Von der Community empfohlene Pakete und deren Versionen (Um schwierigkeiten zu vermeiden)
- Kommerzielle Pakete suchen und installieren - mit Zugangsdaten-Verwaltung
- Ein einfaches Report-System, mit dem wir mögliche Fehlermeldungen analysieren und den Installer verbessern können
- Ein Bewertungssystem / Feedback-System für Module (Bedienbarkeit, Funktion, ... ) wäre denkbar

## Für Entwickler

Der Installer ist frei herunterladbar über Composer und wird auf Github entwickelt.

Das System nutzt für den Ablauf VueJS, Symfony 3.2 (Bald 3.4) und die Composer API. Die Datei, welche schlussendlich ausgeliefert werden soll, wird mit Phing und Box erzeugt.

### Ich möchte bei dem Projekt gerne mitmachen

Das ist gut. Melde dich hierbei am besten bei dem Entwicklerteam im Slack-Channel - oder per E-Mail unter installer@oxid-esales.com ( goi@oxid-esales.com ;D ).

Du kannst uns helfen, falls einer der Punkte dein Interesse weckt:

- Du kannst ein Design für den Installer erstellen
- Du kannst mit CSS das Design umsetzen
- Du kannst mit VueJS das Frontend
- Du kannst eine Authentifizierung mit Symfony und JWT schreiben
- Du kannst mit Symfony und Routen arbeiten
- Du kannst PHP
- Du kannst VueJS
- Du bis ein Marketing-Experte und übersetzt Code zu Customer
- Du kannst mit PHAR arbeiten und möchtest den PHAR-Prozess optimieren
- Du hast Durch und Weitblick und möchtest mit deinen Ideen und Ratschlägen helfen
- Du hast Erfahrung, ein Team von Menschen via Mumble (Oder so) zu koordinieren

### Keiner der Punkte Interessiert mich, was nun?

Wir benötigen ebenfalls:

- Ein Video-Tutorial, wie man ein einfaches Oxid 4 Modul auf Oxid 6 mit Namespaces und Composer umschreibt
- Eine Plattform mit Satis, welche mit einem ausgeklügeltem Authentifizierungssystem kommerzielle Module via Composer anbietet
- Den Composer Cloud Resolver, damit leistungsschwache Server ebenfalls Composer nutzen können
- Geld, Ruhm und Gin
- Eine Doku
