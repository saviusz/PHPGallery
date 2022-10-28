function updateSort(select) {
  const value = select.value;
  const currentLocation = window.location;
  const dest = new URL(currentLocation);
  dest.searchParams.set("sort", value);

  window.location.replace(dest);
}

function setPage(value) {
  const currentLocation = window.location;
  const dest = new URL(currentLocation);
  dest.searchParams.set("page", value);

  window.location.replace(dest);
}
