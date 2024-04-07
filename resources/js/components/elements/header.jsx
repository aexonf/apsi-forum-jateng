import { Link } from "@inertiajs/inertia-react";
import React from "react";
import { AlignJustify } from "lucide-react";
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

export default function Header() {
    return (
        <nav className="w-full fixed bg-gradient-to-br from-primary to-[#efe200] shadow z-40">
            <div className="container mx-auto max-w-3xl px-4 py-2">
                <div className="flex justify-between items-center">
                    <Link
                        href="/"
                        className="scroll-m-20 text-xl font-semibold tracking-tight"
                    >
                        APSI
                    </Link>
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
                                <div className="flex items-center gap-x-4">
                                    <Avatar>
                                        <AvatarImage src="https://github.com/shadcn.png" />
                                        <AvatarFallback>CN</AvatarFallback>
                                    </Avatar>
                                    <div>
                                        <p className="text-sm font-medium leading-none">
                                            shadcn
                                        </p>
                                        <p className="text-xs leading-none text-muted-foreground">
                                            m@example.com
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
                                    <Link href="/forum">Forum</Link>
                                </DropdownMenuItem>
                                <DropdownMenuItem asChild>
                                    <Link href="/publikasi">Publikasi</Link>
                                </DropdownMenuItem>
                            </DropdownMenuGroup>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem
                                className="text-destructive focus:bg-destructive focus:text-destructive-foreground"
                                asChild
                            >
                                <Link href="/logout" method="post">
                                    Keluar
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </nav>
    );
}
