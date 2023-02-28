async function changeAlbumTitle(id, def) {
  const newTitle = prompt("Podaj nowy tytuł", def);

  if (newTitle) {
    const params = new URLSearchParams();
    params.append("id", id);
    params.append("title", newTitle);

    await fetch("./files/modify-album.php", {
      method: "post",
      body: params,
    })
      .then((x) => x.text())
      .then(console.log)
      .then((x) => location.reload());
  }
}

async function deleteAlbum(id) {
  const sure = confirm("Jesteś pewny, że chcesz usunąć album?");

  if (sure) {
    const params = new URLSearchParams();
    params.append("id", id);

    await fetch("./files/delete-album.php", {
      method: "post",
      body: params,
    })
      .then((x) => x.text())
      .then(console.log)
      .then((x) => location.reload());
  }
}

async function modifyPhoto(id, def) {
  const newDesc = prompt("Podaj nowy opis", def);
  if (newDesc) {
    const params = new URLSearchParams();
    params.append("id", id);
    params.append("description", newDesc);

    await fetch("./files/modify-photo.php", {
      method: "post",
      body: params,
    }).then(async (x) => {
      console.log(await x.text());
      location.reload();
    });
  }
}

async function deletePhoto(id) {
  const sure = confirm("Jesteś pewny, że chcesz usunąć to zdjęcie?");

  if (sure) {
    const params = new URLSearchParams();
    params.append("id", id);

    await fetch("./files/delete-photo.php", {
      method: "post",
      body: params,
    }).then((x) => location.reload());
  }
}

async function acceptPhoto(id) {
  const params = new URLSearchParams();
  params.append("id", id);

  await fetch("./files/accept-photo.php", {
    method: "post",
    body: params,
  }).then((x) => location.reload());
}

async function deleteComment(id) {
  const sure = confirm("Jesteś pewny, że chcesz usunąć ten komentarz?");

  if (sure) {
    const params = new URLSearchParams();
    params.append("id", id);

    await fetch("./files/delete-comment.php", {
      method: "post",
      body: params,
    }).then((x) => location.reload());
  }
}

async function acceptComment(id) {
  const params = new URLSearchParams();
  params.append("id", id);

  await fetch("./files/accept-comment.php", {
    method: "post",
    body: params,
  }).then((x) => location.reload());
}

async function modifyComment(id, def) {
  const newContent = prompt("Podaj nową treść", def);

  if (newContent) {
    const params = new URLSearchParams();
    params.append("id", id);
    params.append("content", newContent);

    await fetch("./files/modify-comment.php", {
      method: "post",
      body: params,
    })
      .then((x) => x.text())
      .then(console.log)
      .then((x) => location.reload());
  }
}

async function blockUser(id) {
  const params = new URLSearchParams();
  params.append("id", id);
  params.append("state", "0");

  await fetch("./files/block-user.php", {
    method: "post",
    body: params,
  }).then((x) => location.reload());
}

async function unblockUser(id) {
  const params = new URLSearchParams();
  params.append("id", id);
  params.append("state", "1");

  await fetch("./files/block-user.php", {
    method: "post",
    body: params,
  }).then((x) => location.reload());
}

async function deleteUser(id) {
  const sure = confirm("Jesteś pewny, że chcesz usunąć tego użytkownika?");

  if (sure) {
    const params = new URLSearchParams();
    params.append("id", id);

    await fetch("./files/delete-user.php", {
      method: "post",
      body: params,
    }).then((x) => location.reload());
  }
}

async function changeUserRole(id, elem) {
  const params = new URLSearchParams();
  params.append("id", id);
  params.append("role", elem.value);

  await fetch("./files/modify-user-role.php", {
    method: "post",
    body: params,
  })
    .then((x) => x.text())
    .then(console.log)
    .then((x) => location.reload());
}

function changeAlbum(element) {
  const currentLocation = window.location;
  const dest = new URL(currentLocation);
  dest.searchParams.set("albumId", element.value);

  window.location.replace(dest);
}

function changeCommentsMode() {
  const value = document.querySelector(
    'input[name="comments_type"]:checked'
  ).value;
  const currentLocation = window.location;
  const dest = new URL(currentLocation);
  dest.searchParams.set("commentsMode", value);

  window.location.replace(dest);
}

function changeUserMode() {
  const value = document.querySelector('input[name="user_type"]:checked').value;
  const currentLocation = window.location;
  const dest = new URL(currentLocation);
  dest.searchParams.set("userMode", value);

  window.location.replace(dest);
}
