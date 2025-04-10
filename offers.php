<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вакансии</title>
    <script src="js/messageRansomCars.js"></script>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        session_start();
        include 'template/header.php';
        include 'php/dbconnect.php';
        if (isset($_SESSION['user_id'])) {
            include 'template/nav_employees.php'; // Подключение навигации для аутентифицированных пользователей
        }
    ?>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-center mb-8">Вакансии</h1>
        
        <?php
        // Получаем список вакансий
        $jobsQuery = "SELECT * FROM jobopenings";
        if (!isset($_SESSION['user_id']) || $_SESSION['employee_role'] != 1) {
            $jobsQuery .= " WHERE jobOpenings_in_open = 1";
        }
        $jobsResult = $conn->query($jobsQuery);
        
        while ($job = $jobsResult->fetch_assoc()):
            // Получаем требования для текущей вакансии
            $reqQuery = "SELECT * FROM jobopenings_requirements WHERE jobOpenings_id = " . $job['jobOpenings_id'];
            $reqResult = $conn->query($reqQuery);
        ?>
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold mb-2"><?php echo $job['jobOpenings_names']; ?></h2>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['employee_role'] == 1) { ?>
                        <form action="php/updateJobStatus.php" method="POST">
                            <input type="hidden" name="job_id" value="<?php echo $job['jobOpenings_id']; ?>">
                            <button type="submit" name="toggle_job" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                                <?php echo $job['jobOpenings_in_open'] ? 'Закрыть вакансию' : 'Открыть вакансию'; ?>
                            </button>
                        </form>
                    <?php } ?>
                </div>
                
                <p class="text-gray-700 mb-4"><?php echo $job['jobOpenings_description']; ?></p>
                
                <?php if ($reqResult->num_rows > 0): ?>
                    <h3 class="text-xl font-semibold mb-2">Требования:</h3>
                    <ul class="list-disc list-inside mb-4">
                        <?php while ($req = $reqResult->fetch_assoc()): ?>
                            <li><?php echo $req['jobOpenings_requirements_text']; ?></li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
                
                <h3 class="text-xl font-semibold mb-2">Опыт:</h3>
                <p class="text-gray-700"><?php echo $job['jobOpenings_experiance']; ?></p>
                
                <h3 class="text-xl font-semibold mb-2">Зарплата:</h3>
                <p class="text-gray-700"><?php echo $job['jobOpenings_salary']; ?></p>
            </div>
        <?php endwhile; ?>
    </div>
  <?php
include 'template/footer.php'
?>
</body>
</html>
