let createRoomBtn = document.getElementById("createRoomBtn");
let cancelCreate = document.getElementById("cancelCreate");
let createForm = document.getElementById("createForm");
let refreshBtn = document.getElementById("refreshBtn");
let loadingIndicator = document.getElementById("loadingIndicator");

if (createRoomBtn) {
  createRoomBtn.addEventListener("click", function () {
    createForm.style.display = "block";
  });
}

if (cancelCreate) {
  cancelCreate.addEventListener("click", function () {
    createForm.style.display = "none";
  });
}

if (refreshBtn) {
  refreshBtn.addEventListener("click", function () {
    // يعمل إعادة تحميل للصفحة
    location.reload();
  });

  refreshBtn.addEventListener("click", function () {
    // إظهار اللودر
    loadingIndicator.style.display = "flex";

    // تأخير بسيط عشان يبان اللودر قبل ما يعمل reload
    setTimeout(() => {
      location.reload();
    }, 1000); // 1 ثانية
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const copyBtns = document.querySelectorAll(".copy");

  if (copyBtns) {
    // const roomCode = document.querySelectorAll(".roomCode").innerText; //

    copyBtns.forEach((copyBtn) => {
      copyBtn.addEventListener("click", function () {
        const roomCode = copyBtn.getAttribute("data-code");
        navigator.clipboard
          .writeText(roomCode)
          .then(() => {
            copyBtn.innerText = "Copied!";
            setTimeout(() => (copyBtn.innerText = "Copy"), 2000);
          })
          .catch((err) => {
            console.error("Failed to copy: ", err);
          });
      });
    });
  }
});

// delete Employee

document.addEventListener("DOMContentLoaded", function () {
  const deleteButtons = document.querySelectorAll(".delete");

  deleteButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      const form = this.closest(".delete-form");

      Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to undo after deletion!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});

// // Open Sticky Room

// let viewRoomBtn = document.querySelectorAll('#view_room_btn');

// if(viewRoomBtn){
//   viewRoomBtn.forEach( btn => {
//   let url = btn.getAttribute('data-room-route')
//   btn.addEventListener('click' ,function (){
//     window.open(url,"_blank", "width=300,height=300");
//   })
// })
// }




function checkStatusEmployee() {
  let activationBtn = document.getElementById("activationBtn");
  let activationAlert = document.getElementById("activationAlert");

  if (activationBtn) {
    let user_stats = activationBtn.getAttribute("data-user-status");
    let profileEmployeeSection = document.getElementById(
      "profileEmployeeSection"
    );
    let backdrop = document.getElementById("backdrop");
    if (user_stats == "offline") {
      profileEmployeeSection.addEventListener("click", function () {
        backdrop.classList.add("active");
        activationAlert.classList.add("active");
      });
    }
  }
}


checkStatusEmployee();
