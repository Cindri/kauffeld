// Ändert den Empfangs-Status des Empfängers
function setConfirmed(id, confirmed, obj) {
    if (window.confirm('Möchten Sie den Status des Empfängers wirklich ändern?')) {
        $.post('../classes/PostController/ajax/ajaxHandler.php', {
            handler: 'fghbjnrefdhegwqerivcniawie',
            table: 'newsletter',
            action: 'setConfirmed',
            id: id,
            confirmed: confirmed
        }).done(function (data) {
            if (data != 'Fehler beim Ändern des Status!' && data != 'Schwerer Fehler. Abbruch.') {
                alert(data);
                if (confirmed == 0)  {
                    $(obj).removeClass('glyphicon-ok').addClass('glyphicon-remove');
                    $(obj).css('color', 'red');
                    $(obj).attr("onclick", 'setConfirmed('+id+', 1, this)');
                } else {
                    $(obj).removeClass('glyphicon-remove').addClass('glyphicon-ok');
                    $(obj).css('color', 'green');
                    $(obj).attr("onclick", 'setConfirmed('+id+', 0, this)');
                }
            }
        });
    }
}

// Öffnet die Detailbox zur Bearbeitung eines Eintrags
function edit(id) {
    $('#edit'+id).filter(':hidden').show(300);
    $('#isEdited'+id).attr('value', '1');
}

// Löscht einen Eintrag und lässt die Zeile verschwinden
function deleteEntry(id) {
    if (window.confirm('Möchten Sie den ausgewählten Eintrag wirklich löschen?')) {
        $.post('../classes/PostController/ajax/ajaxHandler.php', {
            handler: 'fghbjnrefdhegwqerivcniawie',
            table: 'newsletter',
            action: 'delete',
            id: id
        }).done(function (data) {
            if (data != 'Fehler beim Löschen des Eintrags!' && data != 'Schwerer Fehler. Abbruch.') {
                $('#repicient' + id).hide(300);
                alert(data);
            }
        });
    }
}
