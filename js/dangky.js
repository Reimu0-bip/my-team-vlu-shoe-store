document.addEventListener('DOMContentLoaded', () => {
  // === 1. KHAI BÁO CÁC PHẦN TỬ (Sử dụng const để cố định biến) ===
  const loginBtn = document.getElementById('loginBtn');
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');
  const goToRegister = document.getElementById('goToRegister');
  const backToLogin = document.getElementById('backToLogin');
  const closeLogin = document.getElementById('closeLogin');
  const closeRegister = document.getElementById('closeRegister');
  
  // Nếu không tìm thấy các phần tử quan trọng, thoát để tránh lỗi
  if (!loginBtn || !loginForm || !registerForm || !goToRegister || !backToLogin || !closeLogin || !closeRegister) {
      console.error("Thiếu một số phần tử modal/login cần thiết trên trang.");
      return;
  }

  // === 2. XỬ LÝ TRẠNG THÁI ĐĂNG NHẬP ===
  const isLoggedOut = loginBtn.textContent.trim() === 'Đăng nhập';

  loginBtn.addEventListener('click', () => {
      if (isLoggedOut) {
          // Nếu chưa đăng nhập, mở form
          loginForm.classList.remove('hidden');
      } else {
          // Nếu đã đăng nhập, hiển thị thông báo
          const username = loginBtn.textContent.trim();
          alert(`Chào mừng ${username} đến với trang web!`);
      }
  });

  // === 3. LOGIC HIỂN THỊ/ẨN MODAL (Đăng nhập <-> Đăng ký) ===
  
  // Chuyển từ Đăng nhập sang Đăng ký
  goToRegister.addEventListener("click", () => {
      loginForm.classList.add("hidden");
      registerForm.classList.remove("hidden");
  });

  // Chuyển từ Đăng ký về Đăng nhập
  backToLogin.addEventListener("click", () => {
      registerForm.classList.add("hidden");
      loginForm.classList.remove("hidden");
  });

  // Đóng Modal (Đăng nhập)
  closeLogin.addEventListener("click", () => loginForm.classList.add("hidden"));
  
  // Đóng Modal (Đăng ký)
  closeRegister.addEventListener("click", () => registerForm.classList.add("hidden"));

  // === 4. VALIDATION ĐĂNG KÝ (Tách biệt logic) ===
  const registerFormContent = document.getElementById("registerFormContent");
  if (registerFormContent) {
      registerFormContent.addEventListener("submit", function(e) {
          const newUsername = document.getElementById("newUsername")?.value.trim();
          const newPassword = document.getElementById("newPassword")?.value.trim();
          const confirmPassword = document.getElementById("confirmPassword")?.value.trim();
          let errorMessage = '';

          // 4a. Kiểm tra đầy đủ thông tin
          if (!newUsername || !newPassword || !confirmPassword) {
              errorMessage = "Vui lòng điền đầy đủ thông tin!";
          
          // 4b. Kiểm tra độ dài mật khẩu
          } else if (newPassword.length < 6) {
              errorMessage = "Mật khẩu phải có ít nhất 6 ký tự!";
          
          // 4c. Kiểm tra khớp mật khẩu
          } else if (newPassword !== confirmPassword) {
              errorMessage = "Mật khẩu và xác nhận mật khẩu không khớp!";
          }

          if (errorMessage) {
              alert(errorMessage);
              e.preventDefault(); // Ngăn form submit
          }
          // Nếu không có lỗi, form sẽ submit bình thường
      });
  }
});