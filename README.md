# 📰 Newsforum

## 📑 Inhaltsverzeichnis
- [1. Tabellenstruktur: tbl_eintraege](#1-tabellenstruktur-tbl_eintraege)
- [2. Tabellenstruktur: tbl_user](#2-tabellenstruktur-tbl_user)
- [3. Relationales Schema](#3-relationales-schema)
- [4. Datenmodellierung & Constraints](#4-datenmodellierung--constraints)
- [5. Begründung der Constraints](#5-begründung-der-constraints)
- [6. Beispiel-Datensätze](#6-beispiel-datensätze)
- [7. Beispiel-Auswertungen](#7-beispiel-auswertungen)

---

## 1. Tabellenstruktur: `tbl_eintraege`

| Spalte       | Typ         | Attribute | NULL | Default                | Extra                      | Kommentar                          |
|--------------|-------------|-----------|------|------------------------|-----------------------------|-------------------------------------|
| id_eintrag   | int(10)     | UNSIGNED  | Nein |                        | PRIMARY KEY, auto_increment| Primärschlüssel                    |
| eintrag      | text        |           | Nein |                        |                             | Beitragstext                        |
| eintragZP    | timestamp   |           | Nein | `current_timestamp()`  | `on update current_timestamp()` | Zeitstempel des Eintrags       |
| fid_user     | int(10)     | UNSIGNED  | Nein |                        |                             | Fremdschlüssel → `tbl_user.id_user` |
| fid_antwort  | int(10)     | UNSIGNED  | Ja   | NULL                   |                             | Selbstreferenz auf Eintrag (Antwort)|

**Fremdschlüssel:**
- `fid_user` → `tbl_user.id_user` (`ON UPDATE CASCADE`, `ON DELETE RESTRICT`)
- `fid_antwort` → `tbl_eintraege.id_eintrag` (`ON UPDATE CASCADE`, `ON DELETE RESTRICT`)

---

## 2. Tabellenstruktur: `tbl_user`

| Spalte      | Typ         | Attribute | NULL | Default               | Extra                      | Kommentar                          |
|-------------|-------------|-----------|------|-----------------------|-----------------------------|-------------------------------------|
| id_user     | int(10)     | UNSIGNED  | Nein |                       | PRIMARY KEY, auto_increment| Primärschlüssel                    |
| email       | varchar(89) |           | Nein |                       | UNIQUE                      | Eindeutige E-Mail-Adresse          |
| pwd         | char(76)    |           | Nein |                       |                             | Passwort (verschlüsselt)           |
| vorname     | varchar(32) |           | Ja   | NULL                  |                             | Vorname (optional)                 |
| nachname    | varchar(32) |           | Ja   | NULL                  |                             | Nachname (optional)                |
| gebDat      | date        |           | Ja   | NULL                  |                             | Geburtsdatum (optional)            |
| regZP       | timestamp   |           | Nein | `current_timestamp()` |                             | Registrierungszeitpunkt            |
| logZP       | timestamp   |           | Nein | `current_timestamp()` | `on update current_timestamp()` | Zeitpunkt des letzten Logins |

---

## 3. Relationales Schema

```
tbl_user (1) --------< (n) tbl_eintraege
                          |
                          └──(0..n) antwortet auf: tbl_eintraege (selbst)
```

- Ein User kann beliebig viele Einträge erstellen.
- Jeder Eintrag kann auf einen anderen Eintrag referenzieren (muss aber nicht).
- Die Beziehung für Antworten ist rekursiv auf `tbl_eintraege`.

---

## 4. Datenmodellierung & Constraints

- **E-Mail:** `UNIQUE NOT NULL` → zur eindeutigen Identifikation.
- **Passwort:** `NOT NULL` → aus Sicherheitsgründen verpflichtend.
- **Vorname/Nachname/Geburtsdatum:** optional (`NULL` erlaubt).
- **regZP** & **logZP:** `NOT NULL` → für Protokollierung.
- **fid_user:** `NOT NULL`, `FOREIGN KEY` → jeder Eintrag gehört zu einem User.
- **fid_antwort:** optional, `FOREIGN KEY` auf `tbl_eintraege` → erlaubt Antworten.
- **ON DELETE RESTRICT:** Schutz vor versehentlichem Löschen.

---

## 5. Begründung der Constraints

- **E-Mail als eindeutiger Login** verhindert doppelte Accounts.
- **Pflichtfelder** wie Passwort und Zeitstempel sichern Login-Logik und Nachvollziehbarkeit.
- **Optionale Felder** (Name, Geburtstag) ermöglichen auch anonyme Nutzung.
- **Selbstreferenzierung** erlaubt verschachtelte Antwortstrukturen (Threaded Diskussionen).
- **ON DELETE RESTRICT** schützt vor versehentlichem Datenverlust (Referenzintegrität).
