import React, { useEffect, useState } from "react";
import { Link } from "@inertiajs/inertia-react";
import { AlignJustify, LogOut } from "lucide-react";
import axios from "axios";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Button } from "../ui/button";
import { Avatar, AvatarFallback, AvatarImage } from "../ui/avatar";
import { Inertia } from "@inertiajs/inertia";
import { toast } from "sonner";

export default function Header() {
    const token = localStorage.getItem("token");
    const [data, setData] = useState(null);

    async function getData() {
        try {
            const response = await axios.get("/api/v1/user", {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            setData(response.data.data);
        } catch (error) {
            toast.error("Gagal mengambil data.");
        }
    }

    useEffect(() => {
        if (token) {
            getData();
        }
    }, []);

    const handleLogout = async (e) => {
        e.preventDefault();
        try {
            const res = await axios.post(
                "/api/v1/auth/logout",
                {},
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            );
            if (res) {
                localStorage.removeItem("token");
                Inertia.get("/login");
                toast.success("Berhasil Logout.");
            }
        } catch (error) {
            toast.error("Gagal Logout.");
        }
    };
    return (
        <nav className="w-full fixed bg-gradient-to-br from-primary to-[#efe200] shadow z-40">
            <div className="container mx-auto max-w-3xl px-4 py-2">
                <div className="flex justify-between items-center">
                    <div className="flex items-center gap-2">
                        <div>
                            <img
                                src="/img/apsi.png"
                                alt="APSI Jateng"
                                className="h-8"
                            />
                        </div>
                        <Link
                            href="/"
                            className="scroll-m-20 text-xl font-bold tracking-tight"
                        >
                            APSI
                        </Link>
                    </div>
                    {token && data ? (
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button
                                    variant="ghost"
                                    className="hover:bg-transparent"
                                >
                                    <AlignJustify className="h-6 w-6" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent
                                className="w-56"
                                align="end"
                                forceMount
                            >
                                <DropdownMenuLabel className="font-normal">
                                    <div className="flex gap-x-4">
                                        <Avatar>
                                            <AvatarImage
                                                src={
                                                    data.img_url
                                                        ? `/storage/${data.img_url}`
                                                        : "/img/avatar/avatar-4.png"
                                                }
                                            />
                                            <AvatarFallback></AvatarFallback>
                                        </Avatar>
                                        <div>
                                            <p className="text-sm font-medium leading-none mb-1">
                                                {data.name}
                                            </p>
                                            <p className="text-xs leading-none text-muted-foreground">
                                                {data.email}
                                            </p>
                                        </div>
                                    </div>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuGroup>
                                    <DropdownMenuItem asChild>
                                        <Link href="/profile">Profil</Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem asChild>
                                        <Link href="/">Forum</Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem asChild>
                                        <Link href="/publikasi">Publikasi</Link>
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem className="">
                                    <Button
                                        className="w-full gap-2"
                                        variant="destructive"
                                        onClick={handleLogout}
                                    >
                                        <LogOut className="size-5" />
                                        Keluar
                                    </Button>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    ) : (
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button
                                    variant="ghost"
                                    className="hover:bg-transparent "
                                >
                                    <AlignJustify className="h-4 w-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent
                                className="w-56"
                                align="end"
                                forceMount
                            >
                                <DropdownMenuLabel className="font-normal">
                                    <Link
                                        href="/login"
                                        className="flex items-center gap-x-4"
                                    >
                                        <Avatar>
                                            <AvatarImage src="" />
                                            <AvatarFallback></AvatarFallback>
                                        </Avatar>
                                        <div>
                                            <p className="text-sm font-medium leading-none mb-1">
                                                Masuk
                                            </p>
                                            <p className="text-xs leading-none text-muted-foreground">
                                                Untuk melanjutkan
                                            </p>
                                        </div>
                                    </Link>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuGroup>
                                    <DropdownMenuItem asChild>
                                        <Link href="/login">Masuk</Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem asChild>
                                        <Link href="/">Forum</Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem asChild>
                                        <Link href="/publikasi">Publikasi</Link>
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    )}
                </div>
            </div>
        </nav>
    );
}
