<?php

function dbConnect(): mysqli
{
    try {
        $conn = new mysqli(
            DB["host"],
            DB["user"],
            DB["pwd"],
            DB["name"]
        );
    } catch (Exception $e) {
        $meldung = "Fehler im verbindungsaufbau";
        $err = $e->getMessage();
        error_log($err);
        exit("{$meldung} {$err}");
    }
    return $conn;
}

function fetch($conn, $q): mixed
{
    return $q->fetch_object();

}

function query($conn, string $sql): mysqli_result|false
{
    try {
        $r = $conn->query($sql);
    } catch (Exception $e) {
        $err = $e->getMessage();
    }


    if (!$r) {
        if (TESTMODE) {
            ta($err);
            exit('<p class="error">Fehler im SQL-Statement</p>');
        } else {
            header("Location: " . DB["errors"]["dbquery"]);
            exit();
        }
    }

    return $r;
}

function value_set(int|float|string $in, bool $allowNull = false): string
{
    return strlen(
        strval($in)
    ) == 0 ? (
        $allowNull ? "NULL" : '""') : '"' . $in . '"';
}
?>