// https://stackoverflow.com/questions/1070760/javascript-href-vs-onclick-for-callback-function-on-hyperlink
document.addEventListener('DOMContentLoaded', () => {
    $('#global-link-legends').click(function() { submitLegends(); return false; });
    $('#global-link-collectors').click(function() { submitCollectors(); return false; });
    $('#global-link-narrators').click(function() { submitNarrators(); return false; });
    $('#global-link-places').click(function() { submitPlaces(); return false; });
    $('#global-link-sources').click(function() { submitSources(); return false; });
});

function submitLegends() {
    document.getElementById("global-submit-legends").click();
}
function submitCollectors() {
    document.getElementById("global-submit-collectors").click();
}
function submitNarrators() {
    document.getElementById("global-submit-narrators").click();
}
function submitPlaces() {
    document.getElementById("global-submit-places").click();
}
function submitSources() {
    document.getElementById("global-submit-sources").click();
}
