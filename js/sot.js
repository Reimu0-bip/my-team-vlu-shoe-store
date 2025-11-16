// Nút cuộn lên đầu trang
document.addEventListener('DOMContentLoaded', (event) => {
    const scrollButton = document.getElementById('scrollToTopBtn');

    // Hàm hiển thị hoặc ẩn nút
    window.onscroll = function() {
        // Hiển thị nút khi cuộn xuống 20px từ đỉnh
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            scrollButton.style.display = "block";
        } else {
            scrollButton.style.display = "none";
        }
    };

    // Hàm xử lý sự kiện khi click vào nút
    scrollButton.addEventListener('click', function(e) {
        e.preventDefault(); // Ngăn chặn hành động chuyển trang của thẻ <a>

        // Cuộn trang mượt mà lên đầu (chỉ hoạt động trên các trình duyệt hiện đại)
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        
    });
});