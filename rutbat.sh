#!/bin/bash
REPO_URL="https://github.com/RutBat228/rutapps.git"
REPO_DIR="$HOME/rutapps"
TV_LIST_FILE="$HOME/.tv_list"

# Функция для проверки и установки ADB
check_and_install_adb() {
    if ! command -v adb &> /dev/null; then
        echo "ADB не установлен. Установка..."
        apt update > /dev/null 2>&1 && apt --assume-yes install wget > /dev/null 2>&1
        wget https://github.com/MasterDevX/Termux-ADB/raw/master/InstallTools.sh -q
        bash InstallTools.sh
        rm InstallTools.sh
        if command -v adb &> /dev/null; then
            echo "ADB успешно установлен."
        else
            echo "Не удалось установить ADB. Пожалуйста, установите его вручную."
            exit 1
        fi
    else
        echo "ADB уже установлен."
    fi
}

# Функция для проверки и обновления/клонирования репозитория
check_and_update_repo() {
    if [ -d "$REPO_DIR" ]; then
        echo "Репозиторий найден. Обновление..."
        cd "$REPO_DIR"
        git pull
        if [ $? -eq 0 ]; then
            echo "Репозиторий успешно обновлен."
        else
            echo "Ошибка при обновлении репозитория."
            exit 1
        fi
    else
        echo "Репозиторий не найден. Клонирование..."
        git clone "$REPO_URL" "$REPO_DIR"
        if [ $? -eq 0 ]; then
            echo "Репозиторий успешно клонирован."
        else
            echo "Ошибка при клонировании репозитория."
            exit 1
        fi
    fi
}

# Функция для отображения списка APK файлов
show_apk_list() {
    echo "Список доступных APK файлов:"
    apk_files=($(ls "$REPO_DIR"/*.apk))
    for i in "${!apk_files[@]}"; do
        echo "$((i+1)). ${apk_files[$i]##*/}"
    done
}

# Функция для установки выбранного APK
install_apk() {
    while true; do
        read -p "Выберите номер APK для установки (0 для выхода): " choice
        if [ $choice -eq 0 ]; then
            return
        elif [ $choice -le ${#apk_files[@]} ]; then
            selected_apk="${apk_files[$((choice-1))]}"
            echo "Установка ${selected_apk##*/}..."
            adb install "$selected_apk"
            if [ $? -eq 0 ]; then
                echo "✅ Установка ${selected_apk##*/} успешно завершена."
            else
                echo "❌ Ошибка при установке приложения ${selected_apk##*/}."
            fi
        else
            echo "Неверный выбор."
        fi
    done
}

# Функция для отображения меню
show_menu() {
    clear
    echo "╔════════════════════════════════════╗"
    echo "║           TV Manager              ║"
    echo "╠════════════════════════════════════╣"
    echo "║ 1. 🔄 Обновить базу приложений     ║"
    echo "║ 2. 📺 Подключиться к телевизору    ║"
    echo "╚════════════════════════════════════╝"
    echo "Введите IP-адрес для прямого подключения"
    echo
    read -p "Выберите действие (1-2) или введите IP: " choice
}

# Функция подключения к ТВ
connect_to_tv() {
    local ip=$1
    adb connect $ip
    if [ $? -eq 0 ]; then
        # Добавляем IP в список только при успешном подключении
        if ! grep -q "^$ip$" "$TV_LIST_FILE"; then
            echo "$ip" >> "$TV_LIST_FILE"
        fi
        clear
        show_apk_list
        install_apk
    else
        echo "Ошибка подключения к $ip"
        sleep 2
    fi
}

# Функция управления списком телевизоров
manage_tv_connection() {
    clear
    echo "📺 Подключение к телевизору"
    echo "------------------------"
    
    # Создаем файл со списком ТВ, если его нет
    touch "$TV_LIST_FILE"
    
    # Показываем сохраненные телевизоры
    echo "Сохраненные телевизоры:"
    if [ -s "$TV_LIST_FILE" ]; then
        cat "$TV_LIST_FILE" | nl
        echo
        read -p "Выберите номер ТВ или введите новый IP: " tv_choice
        if [[ $tv_choice =~ ^[0-9]+$ ]] && [ $tv_choice -le $(wc -l < "$TV_LIST_FILE") ]; then
            tv_ip=$(sed -n "${tv_choice}p" "$TV_LIST_FILE")
            connect_to_tv $tv_ip
        else
            connect_to_tv $tv_choice
        fi
    else
        echo "Список пуст"
        echo
        read -p "Введите IP-адрес телевизора: " tv_ip
        connect_to_tv $tv_ip
    fi
}

# Проверяем наличие ADB при запуске
check_and_install_adb

# Основной цикл программы
while true; do
    show_menu
    if [[ $choice =~ ^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
        # Если введен IP-адрес
        connect_to_tv $choice
    else
        case $choice in
            1)
                clear
                echo "🔄 Обновление базы приложений..."
                check_and_update_repo
                read -n 1 -s -r -p "Нажмите любую клавишу для продолжения..."
                ;;
            2)
                manage_tv_connection
                ;;
            *)
                echo "❌ Неверный выбор"
                sleep 1
                ;;
        esac
    fi
done