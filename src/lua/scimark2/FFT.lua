FFT = class(function(f)
end)

function FFT.bitreverse(v, n)
    local j = 0
    for i=0,2*n-4,2 do
        if i < j then
            v[i+1], v[i+2], v[j+1], v[j+2] = v[j+1], v[j+2], v[i+1], v[i+2]
        end
        local k = n
        while k <= j do j = j - k; k = k / 2 end
        j = j + k
    end
end

function FFT.transform(v, n, dir)
    if n <= 1 then return end
    FFT.bitreverse(v, n)
    local dual = 1
    repeat
        local dual2 = 2*dual
        for i=1,2*n-1,2*dual2 do
            local j = i+dual2
            local ir, ii = v[i], v[i+1]
            local jr, ji = v[j], v[j+1]
            v[j], v[j+1] = ir - jr, ii - ji
            v[i], v[i+1] = ir + jr, ii + ji
        end
        local theta = dir * math.pi / dual
        local s, s2 = math.sin(theta), 2.0 * math.sin(theta * 0.5)^2
        local wr, wi = 1.0, 0.0
        for a=3,dual2-1,2 do
            wr, wi = wr - s*wi - s2*wr, wi + s*wr - s2*wi
            for i=a,a+2*(n-dual2),2*dual2 do
                local j = i+dual2
                local jr, ji = v[j], v[j+1]
                local dr, di = wr*jr - wi*ji, wr*ji + wi*jr
                local ir, ii = v[i], v[i+1]
                v[j], v[j+1] = ir - dr, ii - di
                v[i], v[i+1] = ir + dr, ii + di
            end
        end
        dual = dual2
    until dual >= n
end