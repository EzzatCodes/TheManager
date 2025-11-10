// Manager sticky window logic
let managerStickyWindows = {}; // object to hold references to sticky windows
let viewRoomBtn = document.querySelectorAll("#view_room_btn");
if (viewRoomBtn) {
  viewRoomBtn.forEach((btn) => {
    btn.addEventListener("click", function () {
      let url = btn.getAttribute("data-room-route");
      let roomId = btn.getAttribute("data-room-id");
      if (
        !managerStickyWindows[roomId] ||
        managerStickyWindows[roomId].closed
      ) {
        managerStickyWindows[roomId] = window.open(
          url,
          "_blank",
          "width=300,height=300"
        );
      } else {
        managerStickyWindows[roomId].focus();
      }
    });
  });
}

// employee sticky window logic
let employeeStickyWindow = null; // variable to hold reference to the sticky window

let viewRoomEmployeeBtn = document.querySelectorAll(".viewRoomEmployee");

if (viewRoomEmployeeBtn) {
  viewRoomEmployeeBtn.forEach((employeeBtn) => {
    employeeBtn.addEventListener("click", function () {
      // check about activation Status
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
        } else {
          let url = employeeBtn.getAttribute("data-employee-room-route");

          // لو النافذة مش مفتوحة أو اتقفلت → افتح جديدة
          if (!employeeStickyWindow || employeeStickyWindow.closed) {
            employeeStickyWindow = window.open(
              url,
              "_blank",
              "width=300,height=150"
            );
          } else {
            employeeStickyWindow.focus(); // رجّع التركيز على النافذة المفتوحة
          }
        }
      }
    });
  });
}

// close sticky window logic
// let closeStickyBtn = document.getElementById("closeStickyBtn");
// if(closeStickyBtn) {
//   closeStickyBtn.addEventListener('click', async () => {
//     let roomId = closeStickyBtn.getAttribute('data-room-id');
//     try {
//       await fetch(`/room/sticky/close/${roomId}`, {
//         method: 'POST',
//         headers: {
//           'Content-Type': 'application/json',
//           'X-CSRF-TOKEN':  "{{ csrf_token() }}"
//         },
//         body: JSON.stringify({})
//       });
//       console.log("Closing sticky for room ID:", roomId);
//     }catch (err) {
//       console.error("Error closing sticky:", err);
//       // حتى لو حصل خطأ في الشبكة، نقفل برضه النافذة عشان المستخدم مايتعطّـلش
//     }

//     window.close();
//   });
// }



document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("closeStickyBtn");
  if (!btn) return;

  btn.addEventListener("click", async () => {
    const roomId = btn.dataset.roomId;

    try {
      await fetch(`/room/sticky/close/${roomId}`, {
        method: "POST",
        body: JSON.stringify({}),
      });
    } catch (err) {
      console.error("Error closing sticky:", err);
      // حتى لو حصل خطأ في الشبكة، نقفل برضه النافذة عشان المستخدم مايتعطّـلش
    }

    // اقفل النافذة بعد ما الطلب يتم (حتى لو حصل خطأ)
    window.close();
  });
});
