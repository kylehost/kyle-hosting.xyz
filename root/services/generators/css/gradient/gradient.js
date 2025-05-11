const color1Input = document.getElementById("color1");
const color2Input = document.getElementById("color2");
const angleInput = document.getElementById("angle");
const gradientPreview = document.getElementById("gradientPreview");
const cssOutput = document.getElementById("cssOutput");
const copyButton = document.getElementById("copyButton");
const randomGradientButton = document.getElementById("randomGradientButton");
function updateGradient() {
  const color1 = color1Input.value;
  const color2 = color2Input.value;
  const angle = angleInput.value;
  const gradient = `linear-gradient(${angle}deg, ${color1}, ${color2})`;
  gradientPreview.style.background = gradient;
  cssOutput.value = gradient;
}
function getRandomColor() {
  const letters = "0123456789ABCDEF";
  let color = "#";
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}
function setRandomGradient() {
  color1Input.value = getRandomColor();
  color2Input.value = getRandomColor();
  updateGradient();
}
color1Input.addEventListener("input", updateGradient);
color2Input.addEventListener("input", updateGradient);
angleInput.addEventListener("input", updateGradient);
randomGradientButton.addEventListener("click", setRandomGradient);
copyButton.addEventListener("click", () => {
  cssOutput.select();
  document.execCommand("copy");
  alert("CSS copied to clipboard!");
});
updateGradient();
