let currentMark;

const stars = document.getElementById("stars");
const id = stars.dataset.id;

stars.addEventListener("mousemove", (e) => {
  const element = e.target;

  const rect = element.getBoundingClientRect();
  const x = e.clientX + 10 - rect.left; //x position within the element.

  currentMark = Math.round((x * 10) / rect.width);

  const percent = currentMark * 10 - 0.5;

  element.style.setProperty("--percent", percent + "%");
});

stars.addEventListener("click", () => {
  const data = new FormData();
  data.append("rating", currentMark);
  data.append("id", id);

  fetch("./files/rate-photo.php", {
    method: "POST",
    body: data,
  }).then(() => location.reload());
});

stars.addEventListener("mouseout", (e) => {
  const element = e.target;
  const percent = element.dataset.rating * 10 - 0.5;
  element.style.setProperty("--percent", percent + "%");
});
