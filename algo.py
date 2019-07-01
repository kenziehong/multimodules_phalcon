# Bubble Sort (comparision and swap)
# 1. Kiểm tra độ dài mảng, sau đó for loop theo index
# 2. Với từng index, kiểm tra độ dài còn lại, rồi for loop theo index2
# 3. So sánh từng cặp giá trị tương ứng index2 và index2 + 1, lớn hơn thì swap

nums = [9, 8, 8, 6, 5, 8, 3, 2, 1, 5]

def bubble_sort(arr):
    for index in range(len(arr) -1):
        for index2 in range(len(arr) - 1 - index):
            if arr[index2] > arr[index2 + 1]:
                arr[index2], arr[index2 + 1] = arr[index2 + 1], arr[index2]

bubble_sort(nums)
print(nums)


# Merge Sort(Separations and Partitions - Recursive)
# 1. Tạo 2 hàm, 1 là hàm merge_sort, nhận 1 mảng làm tham số, có:
# - Base case - mảng chỉ có 1 giá trị thì return về giá trị đó
# - Split // 2, rồi đệ quy left, right
# - Return merge(left, right)
# 2. Tạo hàm merge, nhận 2 mảng làm tham số, có
# - Tạo 1 mảng rỗng
# - Chạy vòng lặp while kiểm tra truthy của left và right. 
#     Nếu left[0] < right[0] thì nối left[0] vào mảng rỗng, và lấy dữ liệu đó ra khỏi left, tiếp tục đến hết
# - Nếu chỉ còn mỗi left thì mảng rỗng nối với left và ngược lại right

def merge_sort(arr):
    if len(arr) == 1:
        return arr

    middle_index = len(arr) // 2
    left_split = arr[:middle_index]
    right_split = arr[middle_index:]
    
    left_sorted = merge_sort(left_split)
    right_sorted = merge_sort(right_split)

    return merge(left_sorted, right_sorted)

def merge(left_sorted, right_sorted):
    result = []

    while(left_sorted and right_sorted):
        if left_sorted[0] < right_sorted[0]:
            result.append(left_sorted[0])
            left_sorted.pop(0)
        else:
            result.append(right_sorted[0])
            right_sorted.pop(0)
    if left_sorted:
        result += left_sorted
    if right_sorted:
        result += right_sorted
    return result

merge_sort(nums)
print(nums)







