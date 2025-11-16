window.onload = function() {
    const navType = performance.getEntriesByType("navigation")[0].type;

    if (navType === "reload") {
        window.location.href = "http://localhost:8080/4T_shoe/index.php";
    }
};
