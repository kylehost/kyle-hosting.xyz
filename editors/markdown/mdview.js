const markdownInput = document.getElementById('markdownInput');
const markdownPreview = document.getElementById('markdownPreview');
const copyButton = document.getElementById('copyButton');
function updatePreview() {
    const markdownText = markdownInput.value;
    markdownPreview.innerHTML = marked.parse(markdownText);}
markdownInput.addEventListener('input', updatePreview);
copyButton.addEventListener('click', () => {
    const markdownText = markdownInput.value;
    navigator.clipboard.writeText(markdownText);
    alert('Markdown copied to clipboard!');});updatePreview();