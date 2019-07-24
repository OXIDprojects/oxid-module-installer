# Oxid hackathon 2019, Symfony & Oneklickinstaller

## Testen / Ausführen

### Installieren

`composer create-project oxid-community/moduleinstaller`

### Ausführen

#### Integrierter PHP-Server 

Wenn PHP global installiert ist, kann das Tool mit dem integrierten PHP-Server ausgeführt werden:

`composer run-script server`

Im Browser kann der Installer nun per URL aufgerufen werden: http://localhost:8080

#### Apache/Nginx

Der Installer läuft auch in jeder bekannten Apache-Installation mit PHP wie WampServer, Xampp, Wamp, Lamp, Docker ...

Im Browser kann der Installer per URL aufgerufen werden http://localhost/pfad/moduleinstaller

**Hinweis:** Je nach konfiguration der Domain oder des Ports kann die URL abweichen.

### Routen testen

Alle Routen werden in der Datei `src/Resources/config/routing.yml` notiert. Diese können an die oben genannten URL angehängt werden:

`http://localhost:8080/oxid/moduleinstaller/repositories/`

## Vorgang & Überlegungen

Bei der Themenfindung ist uns relativ schnell klar geworden, dass diese beiden Projekte zusammen entwickelt werden müssen. Der Installer, um die Benutzerfreundlichkeit zu erhöhen und den Kernel der die Pakete entgegen nimmt und bei Bedarf in Oxid integriert und Bundles ohne aufwand nutzbar macht.

## Warum ein Composer installer?

Composer bietet den Vorteil, Software von verschiedenen Webseiten zu installieren und ebenso zu aktualisieren. Eines der wichtigsten Werkzeuge ist dabei die Kontrolle über die kleinste bzw. höchste Version eines zu installierenden Paketes.

Neben den Vorteilen, beklagen mehrere Benutzer über all im Netz zu recht, dass Composer unnötig kompliziert ist. Pakete können nicht gefunden werden, Abhängigkeiten sind veraltet oder falsch definiert, der Arbeitsspeicher reicht nicht aus und die Konsole löst bei einigen unerfahrenen Benutzern unbewusst stress aus.

Um die Vorteile zu nutzen und die Nachteile möglichst zu kompensieren, wurde das Projekt eines Installers gestartet. Wichtig zu erklären, dieses Modul muss gänzlich unabhängig von Oxid funktionieren und es sollte maximal FTP nötig sein, um den Installer hochzuladen. Damit wären Benutzer in der Lage, Oxid mit aufrufen des Installers zu installieren.

### Ablauf

1. Der Installer wird im Oxid-Backend Module installieren können, zumindest dafür wird keine Konsole mehr nötig sein
2. Der Installer wird ohne Oxid nutzbar sein
3. Der Installer wird Pakete von anderen Quellen als Packagist installieren können
4. Firmen werden private Pakete anbieten können

### Vorteile & Pläne

- Shops könnten automatisch per Cronjob aktualisiert werden (Je weniger extra Module umso Update-sicherer)
- Sollte eine Aktualisierung den Shop stören, könnte die alte Version wieder hergestellt werden
- Module und Oxid selbst, können per grafischer Oberfläche installiert werden
- Fehlerausgaben könnten einfacher erklärt werden

Es sollte ebenfalls möglichsein, Module automatisch zu aktivieren und die Datenbank zu aktualisieren nach der Installation oder einem Update.

## Warum Symfony?

Oxid integriert mit Version 6 bereits einige der Symfony 3 Komponenten, es fehlt allerdings noch die Möglichkeit Symfony-Bundles zu installieren und deren Funktionalität über einen Kernel zu aktivieren. Die primäre herausforderung ist es, einen Kernel zu schaffen der automatisch Bundles vial Composer entgegennimmt, aktiviert und weiterhin den normalen Ablauf von Oxid gewährleistet.

Symfony hat sich mit seiner enorm großen Community als stabil und innovativ in der PHP-Welt etabliert. Es bietet Workflows und fordert eine gewisse konsistenz in Code und Schreibweise. Firmen die Beispielsweise Payment-Module herstellen, wäre in der Lage ein Zentrales Tool entwickeln und bräuchten für die Shop welche auf Symfony setzen, nur noch einen Treiber schreiben, der dem jeweiligen Shop erklärt wie die Funktionen des Modules nutzbar sind.

**Hinweis**: Da Oxid auf PHP 7.0 und 7.1 läuft, bzw. diese beiden Versionen kommuniziert, kann nicht Symfony 4 oder höher geladen werden. Einige der Komponenten ab Version 4 erwarten bereits PHP 7.2 und höher.

### Vorteile & Pläne

- Bundles werden nutzbar, das Rad (Bestehende Konzepte und Software) muss nicht neu erfunden werden
- Die Entwicklung der Module wird einheitlicher
- Core-Features können nun Stück für Stück durch Symfony-Features wie Dependeny Injection und Routing ersetzt werden
- Firmen haben es nun einfacher, ihre API/Libs an Oxid anzubinden
- Symfony wird permanent weiterentwickelt. Es wird auf kurz oder lang möglich Versionen für Pakete dynamischer zu definieren, ohne dass Update-Probleme auftauchen

## Warum sind wir nicht fertig geworden?

Das Projekt ist schon sehr weit Fortgeschritten und kann nun von jedem weiterentwickelt bzw. von Entwicklern genutzt werden. Wir bieten jetzt schon den Kernel zur Nutzung an. Die Installation via Composer wird noch etliche Wochen (Monate) dauern. Die reine Funktion sollte relativ schnell aufgestellt sein, der Löwenanteil wird die Herstellung der Kompatibilität für alle Hoster, Entwicklungsumgebungen, Betriebssysteme und Software-Versionen darstellen.

Wir sind in jedem Fall auf etwas Gedult und viel Feedback angewiesen.
