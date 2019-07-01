# Bubble Sort
# 1. Kiểm tra độ dài mảng, sau đó for loop theo index
# 2. Với từng index, kiểm tra độ dài còn lại, rồi for loop theo index2
# 3. So sánh từng cặp giá trị tương ứng index2 và index2 + 1, lớn hơn thì swap

nums = [9, 8, 8, 6, 5, 8, 3, 2, 1, 5]

def bubble(arr):
    for index in range(len(arr) -1):
        for index2 in range(len(arr) - 1 - index):
            if arr[index2] > arr[index2 + 1]:
                arr[index2], arr[index2 + 1] = arr[index2 + 1], arr[index2]

bubble(nums)
print(nums)