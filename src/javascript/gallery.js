function updateSort(select) {
  const value = select.value;
  const currentLocation = window.location;
  const dest = new URL(currentLocation);
  dest.searchParams.set("sort", value);

  console.log(value, currentLocation, dest);

  window.location.replace(dest);
}
