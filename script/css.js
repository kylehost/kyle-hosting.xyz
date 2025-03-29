document.getElementById('minifyButton').addEventListener('click', function() {
    const cssInput = document.getElementById('cssInput').value;
    const minifiedCss = cssInput
        .replace(/\/\*[\s\S]*?\*\//g, '')
        .replace(/\s+/g, ' ')
        .replace(/\s*:\s*/g, ':')
        .replace(/\s*;\s*/g, ';')
        .replace(/\s*\{\s*/g, '{')
        .replace(/\s*\}\s*/g, '}')
        .split('}')
        .map(rule => rule.trim())
        .filter(rule => rule)
        .join('}\n') + '}';
    document.getElementById('minifiedOutput').value = minifiedCss;
});