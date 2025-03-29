document.getElementById('minifyButton').addEventListener('click', function() {
    const jsInput = document.getElementById('jsInput').value;
    const minifiedJs = jsInput
    .replace(/\/\/.*|\/\*[\s\S]*?\*\//g, '')
    .replace(/\s+/g, ' ')
    .trim();
    document.getElementById('minifiedOutput').value = minifiedJs;});