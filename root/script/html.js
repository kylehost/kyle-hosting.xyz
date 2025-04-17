document.getElementById('minifyButton').addEventListener('click', function() {
    const htmlInput = document.getElementById('htmlInput').value;
    const minifiedHtml = htmlInput
    .replace(/<!--[\s\S]*?-->/g, '')
    .replace(/\s+/g, ' ')
    .replace(/>\s+</g, '><')
    .trim();
    document.getElementById('minifiedOutput').value = minifiedHtml;});